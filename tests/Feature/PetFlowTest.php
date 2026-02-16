<?php

namespace Tests\Feature;

use App\Models\application;
use App\Models\pets;
use App\Models\usersdata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_creates_user_and_starts_session(): void
    {
        $response = $this->post('/login', [
            'fullname' => 'Test Adopter',
            'email' => 'adopter@example.com',
            'phone_country' => 'IN',
            'phone' => '1234567890',
            'signup_password' => 'password123',
            'signup_password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('useremail', 'adopter@example.com');

        $this->assertDatabaseHas('usersdatas', [
            'email' => 'adopter@example.com',
            'fullname' => 'Test Adopter',
        ]);
    }

    public function test_user_can_login_with_phone_number(): void
    {
        $user = usersdata::create([
            'fullname' => 'Phone Login User',
            'email' => 'phone-login@example.com',
            'phone' => '1212121212',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->phone,
            'login_password' => 'secret123',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('useremail', $user->email);
    }

    public function test_google_auth_creates_user_and_starts_session(): void
    {
        config(['services.google.client_id' => 'google-client-id-test']);

        Http::fake([
            'oauth2.googleapis.com/tokeninfo*' => Http::response([
                'aud' => 'google-client-id-test',
                'email' => 'google-user@example.com',
                'email_verified' => 'true',
                'name' => 'Google User',
                'picture' => 'https://example.com/avatar.jpg',
            ], 200),
        ]);

        $response = $this->postJson('/auth/google', [
            'credential' => 'mock-google-id-token',
        ]);

        $response->assertOk()->assertJson([
            'redirect' => '/profile',
        ]);
        $response->assertSessionHas('useremail', 'google-user@example.com');

        $this->assertDatabaseHas('usersdatas', [
            'email' => 'google-user@example.com',
            'fullname' => 'Google User',
        ]);
    }

    public function test_logged_in_user_can_list_pet(): void
    {
        $user = usersdata::create([
            'fullname' => 'Lister User',
            'email' => 'lister@example.com',
            'phone' => '1112223333',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->withSession(['useremail' => $user->email])->post('/listapet', [
            'petname' => 'Buddy',
            'breed' => 'Labrador',
            'age' => 3,
            'Description' => 'Friendly and playful',
            'gender' => 'Male',
            'size' => 'medium',
            'Location' => 'Austin',
        ]);

        $response->assertRedirect('/mylisting');

        $this->assertDatabaseHas('pets', [
            'name' => 'Buddy',
            'user_id' => $user->id,
            'city' => 'Austin',
        ]);
    }

    public function test_user_can_apply_to_pet_only_once(): void
    {
        $owner = usersdata::create([
            'fullname' => 'Owner User',
            'email' => 'owner@example.com',
            'phone' => '1111111111',
            'password' => bcrypt('secret123'),
        ]);

        $applicant = usersdata::create([
            'fullname' => 'Applicant User',
            'email' => 'applicant@example.com',
            'phone' => '2222222222',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Milo',
            'breed' => 'Beagle',
            'age' => 2,
            'gender' => 'Male',
            'size' => 'small',
            'description' => 'Calm dog',
            'city' => 'Dallas',
            'user_id' => $owner->id,
        ]);

        $first = $this->withSession(['useremail' => $applicant->email])->post('/petdetails/'.$pet->id.'/apply', [
            'description' => 'I have a big backyard.',
        ]);

        $first->assertRedirect('/myapplication');
        $this->assertDatabaseHas('applications', [
            'user_id' => $applicant->id,
            'pet_id' => $pet->id,
            'status' => 'pending',
        ]);

        $second = $this->withSession(['useremail' => $applicant->email])->post('/petdetails/'.$pet->id.'/apply');
        $second->assertSessionHas('error');

        $this->assertSame(1, application::where('user_id', $applicant->id)->where('pet_id', $pet->id)->count());
    }
}
