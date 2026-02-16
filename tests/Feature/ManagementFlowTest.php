<?php

namespace Tests\Feature;

use App\Mail\LostPetAlertMail;
use App\Models\application;
use App\Models\LostPetRequest;
use App\Models\Message;
use App\Models\pets;
use App\Models\usersdata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManagementFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_approve_application_and_notify_applicant(): void
    {
        $owner = usersdata::create([
            'fullname' => 'Owner One',
            'email' => 'owner1@example.com',
            'phone' => '1111111111',
            'password' => bcrypt('secret123'),
        ]);

        $applicant = usersdata::create([
            'fullname' => 'Applicant One',
            'email' => 'applicant1@example.com',
            'phone' => '2222222222',
            'password' => bcrypt('secret123'),
        ]);

        $applicantTwo = usersdata::create([
            'fullname' => 'Applicant Two',
            'email' => 'applicant2@example.com',
            'phone' => '3333333333',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Rocky',
            'breed' => 'Husky',
            'age' => 2,
            'gender' => 'Male',
            'size' => 'large',
            'description' => 'Playful',
            'city' => 'Austin',
            'user_id' => $owner->id,
        ]);

        $primaryApplication = application::create([
            'status' => 'pending',
            'user_id' => $applicant->id,
            'pet_id' => $pet->id,
            'description' => 'I would love to adopt Rocky.',
        ]);

        application::create([
            'status' => 'pending',
            'user_id' => $applicantTwo->id,
            'pet_id' => $pet->id,
            'description' => 'Second applicant',
        ]);

        $response = $this->withSession(['useremail' => $owner->email])->post(
            '/mylisting/'.$pet->id.'/applications/'.$primaryApplication->id.'/status',
            [
                'status' => 'approved',
                'note' => 'See you this weekend.',
            ]
        );

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'id' => $primaryApplication->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('applications', [
            'user_id' => $applicantTwo->id,
            'pet_id' => $pet->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'adopted' => 1,
        ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $owner->id,
            'receiver_id' => $applicant->id,
            'pet_id' => $pet->id,
        ]);
    }

    public function test_user_can_update_profile_and_password_from_settings(): void
    {
        $user = usersdata::create([
            'fullname' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '4444444444',
            'password' => bcrypt('oldpassword'),
        ]);

        $profileUpdate = $this->withSession(['useremail' => $user->email])->post('/settings/profile', [
            'fullname' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '5555555555',
        ]);

        $profileUpdate->assertSessionHas('success');
        $profileUpdate->assertSessionHas('useremail', 'updated@example.com');

        $this->assertDatabaseHas('usersdatas', [
            'id' => $user->id,
            'fullname' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '5555555555',
        ]);

        $passwordUpdate = $this->withSession(['useremail' => 'updated@example.com'])->post('/settings/password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $passwordUpdate->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_owner_can_edit_pet_listing_from_mylisting_page(): void
    {
        $owner = usersdata::create([
            'fullname' => 'Listing Owner',
            'email' => 'edit-owner@example.com',
            'phone' => '5050505050',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Bruno',
            'breed' => 'Indie',
            'age' => 4,
            'gender' => 'Male',
            'size' => 'medium',
            'description' => 'Friendly and calm',
            'city' => 'Pune',
            'user_id' => $owner->id,
        ]);

        $editPage = $this->withSession(['useremail' => $owner->email])->get('/mylisting/'.$pet->id.'/edit');
        $editPage->assertOk();
        $editPage->assertSee('Edit Listing');

        $update = $this->withSession(['useremail' => $owner->email])->post('/mylisting/'.$pet->id.'/edit', [
            'petname' => 'Bruno Updated',
            'breed' => 'Labrador',
            'age' => 5,
            'Description' => 'Very playful and active',
            'gender' => 'Male',
            'size' => 'large',
            'Location' => 'Mumbai',
        ]);

        $update->assertRedirect('/mylisting');
        $update->assertSessionHas('success');

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'Bruno Updated',
            'breed' => 'Labrador',
            'age' => 5,
            'description' => 'Very playful and active',
            'size' => 'large',
            'city' => 'Mumbai',
            'user_id' => $owner->id,
        ]);
    }

    public function test_owner_can_open_direct_chat_with_applicant_from_applications_page(): void
    {
        $owner = usersdata::create([
            'fullname' => 'Owner Direct Chat',
            'email' => 'owner-direct-chat@example.com',
            'phone' => '6060606060',
            'password' => bcrypt('secret123'),
        ]);

        $applicant = usersdata::create([
            'fullname' => 'Applicant Direct Chat',
            'email' => 'applicant-direct-chat@example.com',
            'phone' => '7070707070',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Lucy',
            'breed' => 'Beagle',
            'age' => 2,
            'gender' => 'Female',
            'size' => 'small',
            'description' => 'Energetic and loving',
            'city' => 'Denver',
            'user_id' => $owner->id,
        ]);

        application::create([
            'status' => 'pending',
            'user_id' => $applicant->id,
            'pet_id' => $pet->id,
            'description' => 'I have experience with beagles.',
        ]);

        $applicationsPage = $this->withSession(['useremail' => $owner->email])->get('/mylisting/'.$pet->id.'/applications');
        $applicationsPage->assertOk();
        $applicationsPage->assertSee('/messages?thread='.urlencode($applicant->id.'|'.$pet->id), false);

        $messagesPage = $this->withSession(['useremail' => $owner->email])->get('/messages?thread='.$applicant->id.'|'.$pet->id);
        $messagesPage->assertOk();
        $messagesPage->assertSee('Applicant Direct Chat');
        $messagesPage->assertSee('No messages in this thread yet.');
        $messagesPage->assertSee('About pet: '.$pet->name);
    }

    public function test_user_can_send_message_when_related_to_pet_application(): void
    {
        $owner = usersdata::create([
            'fullname' => 'Owner Msg',
            'email' => 'owner-msg@example.com',
            'phone' => '6666666666',
            'password' => bcrypt('secret123'),
        ]);

        $applicant = usersdata::create([
            'fullname' => 'Applicant Msg',
            'email' => 'applicant-msg@example.com',
            'phone' => '7777777777',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Bella',
            'breed' => 'Terrier',
            'age' => 1,
            'gender' => 'Female',
            'size' => 'small',
            'description' => 'Sweet dog',
            'city' => 'Dallas',
            'user_id' => $owner->id,
        ]);

        application::create([
            'status' => 'pending',
            'user_id' => $applicant->id,
            'pet_id' => $pet->id,
            'description' => 'Please consider my application.',
        ]);

        $response = $this->withSession(['useremail' => $applicant->email])->post('/messages', [
            'receiver_id' => $owner->id,
            'pet_id' => $pet->id,
            'body' => 'Hello, I would like to share more details about my home.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'sender_id' => $applicant->id,
            'receiver_id' => $owner->id,
            'pet_id' => $pet->id,
        ]);

        $this->assertTrue(Message::query()->exists());
    }

    public function test_applicant_can_message_owner_after_approval_with_string_receiver_id(): void
    {
        $owner = usersdata::create([
            'fullname' => 'Owner Approved',
            'email' => 'owner-approved@example.com',
            'phone' => '1010101010',
            'password' => bcrypt('secret123'),
        ]);

        $applicant = usersdata::create([
            'fullname' => 'Applicant Approved',
            'email' => 'applicant-approved@example.com',
            'phone' => '2020202020',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Nova',
            'breed' => 'Indie',
            'age' => 2,
            'gender' => 'Female',
            'size' => 'medium',
            'description' => 'Gentle dog',
            'city' => 'Seattle',
            'user_id' => $owner->id,
        ]);

        application::create([
            'status' => 'approved',
            'user_id' => $applicant->id,
            'pet_id' => $pet->id,
            'description' => 'Approved application',
        ]);

        $response = $this->withSession(['useremail' => $applicant->email])->post('/messages', [
            'receiver_id' => (string) $owner->id,
            'pet_id' => $pet->id,
            'body' => 'Thanks for approving. When can I visit?',
        ]);

        $response->assertRedirect('/messages?thread='.$owner->id.'|'.$pet->id);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $applicant->id,
            'receiver_id' => $owner->id,
            'pet_id' => $pet->id,
            'body' => 'Thanks for approving. When can I visit?',
        ]);
    }

    public function test_profile_saved_and_unread_counts_are_dynamic_and_unread_updates_after_viewing_messages(): void
    {
        $user = usersdata::create([
            'fullname' => 'Profile User',
            'email' => 'profile-user@example.com',
            'phone' => '8888888888',
            'password' => bcrypt('secret123'),
        ]);

        $owner = usersdata::create([
            'fullname' => 'Listing Owner',
            'email' => 'listing-owner@example.com',
            'phone' => '9999999999',
            'password' => bcrypt('secret123'),
        ]);

        $pet = pets::create([
            'name' => 'Coco',
            'breed' => 'Mixed Breed',
            'age' => 3,
            'gender' => 'Female',
            'size' => 'medium',
            'description' => 'Friendly dog',
            'city' => 'Boston',
            'user_id' => $owner->id,
        ]);

        application::create([
            'status' => 'pending',
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'description' => 'I can provide a loving home.',
        ]);

        Message::create([
            'sender_id' => $owner->id,
            'receiver_id' => $user->id,
            'pet_id' => $pet->id,
            'body' => 'Thanks for applying!',
        ]);

        Message::create([
            'sender_id' => $owner->id,
            'receiver_id' => $user->id,
            'pet_id' => $pet->id,
            'body' => 'Can we schedule a call?',
        ]);

        $profileBefore = $this->withSession(['useremail' => $user->email])->get('/profile');
        $profileBefore->assertOk();
        $profileBefore->assertViewHas('savedPetsCount', 1);
        $profileBefore->assertViewHas('unreadMessagesCount', 2);

        $messagesPage = $this->withSession(['useremail' => $user->email])->get('/messages');
        $messagesPage->assertOk();

        $profileAfter = $this->withSession(['useremail' => $user->email])->get('/profile');
        $profileAfter->assertOk();
        $profileAfter->assertViewHas('unreadMessagesCount', 0);
    }

    public function test_rejected_application_is_not_counted_in_saved_dogs(): void
    {
        $user = usersdata::create([
            'fullname' => 'Rejected Count User',
            'email' => 'rejected-count-user@example.com',
            'phone' => '9191919191',
            'password' => bcrypt('secret123'),
        ]);

        $owner = usersdata::create([
            'fullname' => 'Rejected Count Owner',
            'email' => 'rejected-count-owner@example.com',
            'phone' => '9292929292',
            'password' => bcrypt('secret123'),
        ]);

        $pendingPet = pets::create([
            'name' => 'Penny',
            'breed' => 'Labrador',
            'age' => 2,
            'gender' => 'Female',
            'size' => 'medium',
            'description' => 'Happy dog',
            'city' => 'Miami',
            'user_id' => $owner->id,
        ]);

        $rejectedPet = pets::create([
            'name' => 'Rex',
            'breed' => 'Husky',
            'age' => 4,
            'gender' => 'Male',
            'size' => 'large',
            'description' => 'Energetic dog',
            'city' => 'Orlando',
            'user_id' => $owner->id,
        ]);

        application::create([
            'status' => 'pending',
            'user_id' => $user->id,
            'pet_id' => $pendingPet->id,
            'description' => 'Pending application.',
        ]);

        application::create([
            'status' => 'rejected',
            'user_id' => $user->id,
            'pet_id' => $rejectedPet->id,
            'description' => 'Rejected application.',
        ]);

        $profile = $this->withSession(['useremail' => $user->email])->get('/profile');
        $profile->assertOk();
        $profile->assertViewHas('savedPetsCount', 1);
    }

    public function test_profile_browse_dogs_shows_all_active_dogs_for_every_user(): void
    {
        $user = usersdata::create([
            'fullname' => 'Browse User',
            'email' => 'browse-user@example.com',
            'phone' => '3131313131',
            'password' => bcrypt('secret123'),
        ]);

        $otherOwner = usersdata::create([
            'fullname' => 'Other Owner',
            'email' => 'other-owner@example.com',
            'phone' => '4141414141',
            'password' => bcrypt('secret123'),
        ]);

        $ownActivePet = pets::create([
            'name' => 'Self Listed',
            'breed' => 'Indian Pariah',
            'age' => 2,
            'gender' => 'Male',
            'size' => 'medium',
            'description' => 'Own active pet',
            'city' => 'Pune',
            'user_id' => $user->id,
            'adopted' => false,
        ]);

        $otherActivePet = pets::create([
            'name' => 'Other Active',
            'breed' => 'Beagle',
            'age' => 3,
            'gender' => 'Female',
            'size' => 'small',
            'description' => 'Other active pet',
            'city' => 'Berlin',
            'user_id' => $otherOwner->id,
            'adopted' => false,
        ]);

        $adoptedPet = pets::create([
            'name' => 'Already Adopted',
            'breed' => 'Labrador',
            'age' => 5,
            'gender' => 'Female',
            'size' => 'large',
            'description' => 'Should be hidden',
            'city' => 'Sydney',
            'user_id' => $otherOwner->id,
            'adopted' => true,
        ]);

        $profile = $this->withSession(['useremail' => $user->email])->get('/profile');
        $profile->assertOk();
        $profile->assertViewHas('browseDogs', function ($browseDogs) use ($ownActivePet, $otherActivePet, $adoptedPet) {
            return $browseDogs->contains('id', $ownActivePet->id)
                && $browseDogs->contains('id', $otherActivePet->id)
                && !$browseDogs->contains('id', $adoptedPet->id);
        });
    }

    public function test_lost_pet_request_is_shared_with_same_city_users_and_sends_email(): void
    {
        Mail::fake();
        Storage::fake('public');

        $requester = usersdata::create([
            'fullname' => 'Lost Pet Requester',
            'email' => 'lost-requester@example.com',
            'phone' => '5151515151',
            'location' => 'Pune',
            'password' => bcrypt('secret123'),
        ]);

        $sameCityUser = usersdata::create([
            'fullname' => 'Same City User',
            'email' => 'same-city@example.com',
            'phone' => '6161616161',
            'location' => 'Pune',
            'password' => bcrypt('secret123'),
        ]);

        $otherCityUser = usersdata::create([
            'fullname' => 'Other City User',
            'email' => 'other-city@example.com',
            'phone' => '7171717171',
            'location' => 'Mumbai',
            'password' => bcrypt('secret123'),
        ]);

        $submit = $this->withSession(['useremail' => $requester->email])
            ->post('/lost-pet-requests', [
                'pet_name' => 'Tommy',
                'city' => 'pune',
                'last_seen_area' => 'FC Road',
                'description' => 'Brown dog wearing a red collar.',
                'contact_phone' => '5151515151',
                'photos' => [UploadedFile::fake()->image('tommy.jpg')],
            ]);

        $submit->assertSessionHas('success');
        $submit->assertRedirect('/lost-pet-requests');

        $this->assertDatabaseHas('lost_pet_requests', [
            'user_id' => $requester->id,
            'pet_name' => 'Tommy',
            'city' => 'Pune',
            'status' => 'open',
        ]);

        $lostRequest = LostPetRequest::firstOrFail();
        $this->assertIsArray($lostRequest->photos);
        $this->assertNotEmpty($lostRequest->photos);
        Storage::disk('public')->assertExists($lostRequest->photos[0]);

        $sameCityProfile = $this->withSession(['useremail' => $sameCityUser->email])->get('/lost-pet-requests');
        $sameCityProfile->assertOk();
        $sameCityProfile->assertViewHas('localLostPetRequests', fn ($alerts) => $alerts->contains('id', $lostRequest->id));

        $otherCityProfile = $this->withSession(['useremail' => $otherCityUser->email])->get('/lost-pet-requests');
        $otherCityProfile->assertOk();
        $otherCityProfile->assertViewHas('localLostPetRequests', fn ($alerts) => !$alerts->contains('id', $lostRequest->id));

        Mail::assertSent(LostPetAlertMail::class, 1);
        Mail::assertSent(LostPetAlertMail::class, fn (LostPetAlertMail $mail) => $mail->hasTo($sameCityUser->email));
        Mail::assertNotSent(LostPetAlertMail::class, fn (LostPetAlertMail $mail) => $mail->hasTo($otherCityUser->email));
    }
}
