<?php

namespace Database\Seeders;

use App\Models\application;
use App\Models\LostPetRequest;
use App\Models\Message;
use App\Models\pets;
use App\Models\User;
use App\Models\usersdata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PetFinderDemoSeeder extends Seeder
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $tableColumnsCache = [];

    /**
     * @var array<string, int>
     */
    private array $authUserIdsByEmail = [];

    /**
     * Seed rich demo data for local/project use.
     */
    public function run(): void
    {
        if (!Schema::hasTable('usersdatas') || !Schema::hasTable('pets')) {
            return;
        }

        $password = Hash::make('password123');

        $users = [
            'nisha' => $this->upsertUser(
                'Nisha Kulkarni',
                'nisha.kulkarni@example.com',
                '+919812345670',
                'Pune',
                'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop'
            , $password),
            'rahul' => $this->upsertUser(
                'Rahul Mehta',
                'rahul.mehta@example.com',
                '+919812345671',
                'Mumbai',
                'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&h=300&fit=crop'
            , $password),
            'sarah' => $this->upsertUser(
                'Sarah Johnson',
                'sarah.johnson@example.com',
                '+12025550111',
                'Austin',
                'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop'
            , $password),
            'michael' => $this->upsertUser(
                'Michael Brown',
                'michael.brown@example.com',
                '+12025550112',
                'Seattle',
                'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=300&h=300&fit=crop'
            , $password),
            'anna' => $this->upsertUser(
                'Anna Schmidt',
                'anna.schmidt@example.com',
                '+4915112345678',
                'Berlin',
                'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=300&h=300&fit=crop'
            , $password),
            'lukas' => $this->upsertUser(
                'Lukas Weber',
                'lukas.weber@example.com',
                '+4915112345679',
                'Berlin',
                'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop'
            , $password),
            'emma' => $this->upsertUser(
                'Emma Wilson',
                'emma.wilson@example.com',
                '+61400111222',
                'Sydney',
                'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=300&h=300&fit=crop'
            , $password),
            'oliver' => $this->upsertUser(
                'Oliver Smith',
                'oliver.smith@example.com',
                '+61400111223',
                'Melbourne',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop'
            , $password),
            'priya' => $this->upsertUser(
                'Priya Desai',
                'priya.desai@example.com',
                '+919812345672',
                'Pune',
                'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop'
            , $password),
            'daniel' => $this->upsertUser(
                'Daniel Clark',
                'daniel.clark@example.com',
                '+12025550113',
                'Austin',
                'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?w=300&h=300&fit=crop'
            , $password),
            'meera' => $this->upsertUser(
                'Meera Patil',
                'meera.patil@example.com',
                '+919812345673',
                'Pune',
                'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=300&h=300&fit=crop'
            , $password),
            'arjun' => $this->upsertUser(
                'Arjun Rao',
                'arjun.rao@example.com',
                '+919812345674',
                'Bengaluru',
                'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&h=300&fit=crop'
            , $password),
            'zoe' => $this->upsertUser(
                'Zoe Miller',
                'zoe.miller@example.com',
                '+12025550114',
                'Seattle',
                'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=300&h=300&fit=crop'
            , $password),
            'liam' => $this->upsertUser(
                'Liam Green',
                'liam.green@example.com',
                '+61400111224',
                'Brisbane',
                'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop'
            , $password),
        ];

        $this->seedAuthUsers($users, $password);

        $petRecords = array_merge([
            ['name' => 'Bruno', 'owner' => 'nisha', 'breed' => 'Labrador', 'age' => 3, 'gender' => 'Male', 'size' => 'large', 'city' => 'Pune', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1517849845537-4d257902454a?w=800&h=1000&fit=crop', 'description' => 'Friendly and playful Labrador who loves long walks, fetch, and children. Vaccinated and healthy.'],
            ['name' => 'Mochi', 'owner' => 'nisha', 'breed' => 'Indie Mix', 'age' => 2, 'gender' => 'Female', 'size' => 'medium', 'city' => 'Pune', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=800&h=1000&fit=crop', 'description' => 'Gentle indie mix rescued from roadside. Calm temperament, good with apartment living, and leash trained.'],
            ['name' => 'Rocky', 'owner' => 'rahul', 'breed' => 'German Shepherd', 'age' => 4, 'gender' => 'Male', 'size' => 'large', 'city' => 'Mumbai', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?w=800&h=1000&fit=crop', 'description' => 'Loyal and intelligent dog looking for an active family. Knows basic commands and enjoys outdoor activities.'],
            ['name' => 'Coco', 'owner' => 'rahul', 'breed' => 'Beagle', 'age' => 1, 'gender' => 'Female', 'size' => 'small', 'city' => 'Mumbai', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1505628346881-b72b27e84530?w=800&h=1000&fit=crop', 'description' => 'Curious beagle puppy, highly social and playful. Ideal for a family ready to continue basic training.'],
            ['name' => 'Buddy', 'owner' => 'sarah', 'breed' => 'Golden Retriever', 'age' => 5, 'gender' => 'Male', 'size' => 'large', 'city' => 'Austin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=800&h=1000&fit=crop', 'description' => 'Very affectionate retriever with excellent home manners. Great with kids and other dogs.'],
            ['name' => 'Luna', 'owner' => 'sarah', 'breed' => 'Husky', 'age' => 3, 'gender' => 'Female', 'size' => 'medium', 'city' => 'Austin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1525253086316-d0c936c814f8?w=800&h=1000&fit=crop', 'description' => 'Energetic husky who needs regular exercise and an experienced adopter. House-trained and friendly.'],
            ['name' => 'Charlie', 'owner' => 'michael', 'breed' => 'Border Collie', 'age' => 2, 'gender' => 'Male', 'size' => 'medium', 'city' => 'Seattle', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1537151625747-768eb6cf92b2?w=800&h=1000&fit=crop', 'description' => 'Smart and energetic collie. Loves puzzle games and agility. Best suited for an active home.'],
            ['name' => 'Daisy', 'owner' => 'michael', 'breed' => 'Cocker Spaniel', 'age' => 6, 'gender' => 'Female', 'size' => 'small', 'city' => 'Seattle', 'adopted' => true, 'image_url' => 'https://images.unsplash.com/photo-1517423440428-a5a00ad493e8?w=800&h=1000&fit=crop', 'description' => 'Sweet senior spaniel with calm nature. Adopted recently after successful meet-and-greet process.'],
            ['name' => 'Max', 'owner' => 'anna', 'breed' => 'Boxer', 'age' => 4, 'gender' => 'Male', 'size' => 'large', 'city' => 'Berlin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1534361960057-19889db9621e?w=800&h=1000&fit=crop', 'description' => 'Strong, confident boxer who is very loving once comfortable. Prefers adults and structured routine.'],
            ['name' => 'Nala', 'owner' => 'anna', 'breed' => 'Mixed Breed', 'age' => 2, 'gender' => 'Female', 'size' => 'medium', 'city' => 'Berlin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1535930749574-1399327ce78f?w=800&h=1000&fit=crop', 'description' => 'Rescued mixed-breed with cheerful personality. Good with kids and responds well to positive reinforcement.'],
            ['name' => 'Archie', 'owner' => 'lukas', 'breed' => 'Dachshund', 'age' => 3, 'gender' => 'Male', 'size' => 'small', 'city' => 'Berlin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=800&h=1000&fit=crop', 'description' => 'Adorable dachshund with playful attitude. Enjoys short walks and cuddles. Suitable for apartment life.'],
            ['name' => 'Poppy', 'owner' => 'emma', 'breed' => 'Kelpie Mix', 'age' => 1, 'gender' => 'Female', 'size' => 'medium', 'city' => 'Sydney', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1477884213360-7e9d7dcc1e48?w=800&h=1000&fit=crop', 'description' => 'Young and energetic kelpie mix. Needs a home that can provide daily activity and mental stimulation.'],
            ['name' => 'Bentley', 'owner' => 'oliver', 'breed' => 'Corgi', 'age' => 2, 'gender' => 'Male', 'size' => 'small', 'city' => 'Melbourne', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&h=1000&fit=crop', 'description' => 'Friendly corgi with charming personality. Loves social interaction and short game sessions.'],
            ['name' => 'Shadow', 'owner' => 'priya', 'breed' => 'Indie', 'age' => 5, 'gender' => 'Male', 'size' => 'medium', 'city' => 'Pune', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?w=800&h=1000&fit=crop', 'description' => 'Independent but affectionate indie dog. Comfortable in both apartment and house settings with regular walks.'],
            ['name' => 'Milo', 'owner' => 'daniel', 'breed' => 'Labradoodle', 'age' => 2, 'gender' => 'Male', 'size' => 'medium', 'city' => 'Austin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&h=1000&fit=crop', 'description' => 'Social and easy-going labradoodle. Great for families, very good with children and first-time adopters.'],
        ], $this->extraPetRecords());

        $petsByName = [];
        foreach ($petRecords as $petData) {
            $owner = $users[$petData['owner']];
            $pet = pets::updateOrCreate(
                $this->pickExistingColumns('pets', [
                    'name' => $petData['name'],
                    'user_id' => $owner->id,
                ]),
                $this->pickExistingColumns('pets', [
                    'gender' => $petData['gender'],
                    'breed' => $petData['breed'],
                    'age' => $petData['age'],
                    'size' => $petData['size'],
                    'description' => $petData['description'],
                    'image_url' => $petData['image_url'],
                    'adopted' => $petData['adopted'],
                    'city' => $petData['city'],
                ])
            );
            $petsByName[$petData['name']] = $pet;
        }

        $applications = array_merge([
            ['applicant' => 'priya', 'pet' => 'Buddy', 'status' => 'approved', 'description' => 'I have a fenced yard and previous retriever experience.'],
            ['applicant' => 'daniel', 'pet' => 'Luna', 'status' => 'pending', 'description' => 'I can commit to daily runs and training sessions.'],
            ['applicant' => 'nisha', 'pet' => 'Archie', 'status' => 'pending', 'description' => 'Looking for a small dog for my apartment.'],
            ['applicant' => 'rahul', 'pet' => 'Charlie', 'status' => 'rejected', 'description' => 'Applied but timeline changed due to relocation.'],
            ['applicant' => 'emma', 'pet' => 'Mochi', 'status' => 'pending', 'description' => 'Can provide a calm and loving home environment.'],
            ['applicant' => 'lukas', 'pet' => 'Bentley', 'status' => 'approved', 'description' => 'Work from home and can provide constant care.'],
            ['applicant' => 'michael', 'pet' => 'Nala', 'status' => 'pending', 'description' => 'Have experience with rescue dogs and basic training.'],
            ['applicant' => 'sarah', 'pet' => 'Shadow', 'status' => 'pending', 'description' => 'Looking for a companion for our family home.'],
            ['applicant' => 'oliver', 'pet' => 'Coco', 'status' => 'pending', 'description' => 'Can offer active routine and regular vet visits.'],
            ['applicant' => 'anna', 'pet' => 'Milo', 'status' => 'pending', 'description' => 'Prepared with supplies and adoption plan.'],
        ], $this->extraApplications());

        if (Schema::hasTable('applications')) {
            $applicationsUserTable = $this->getReferencedTable('applications', 'user_id');

            foreach ($applications as $applicationData) {
                $applicant = $users[$applicationData['applicant']];
                $pet = $petsByName[$applicationData['pet']] ?? null;
                if (!$pet) {
                    continue;
                }

                $applicantId = $applicant->id;
                if ($applicationsUserTable === 'users') {
                    $applicantId = $this->authUserIdsByEmail[$applicant->email] ?? null;
                }

                if (!$applicantId) {
                    continue;
                }

                application::updateOrCreate(
                    $this->pickExistingColumns('applications', [
                        'user_id' => $applicantId,
                        'pet_id' => $pet->id,
                    ]),
                    $this->pickExistingColumns('applications', [
                        'status' => $applicationData['status'],
                        'description' => $applicationData['description'],
                    ])
                );
            }
        }

        if (Schema::hasTable('messages')) {
            $this->seedMessages($users, $petsByName);
        }

        if (Schema::hasTable('lost_pet_requests')) {
            $this->seedLostPetRequests($users);
        }
    }

    private function upsertUser(
        string $fullname,
        string $email,
        string $phone,
        string $location,
        ?string $profilePicture,
        string $password
    ): usersdata {
        $lookup = $this->pickExistingColumns('usersdatas', ['email' => $email]);
        if (empty($lookup)) {
            return new usersdata();
        }

        return usersdata::updateOrCreate(
            $lookup,
            $this->pickExistingColumns('usersdatas', [
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'location' => $location,
                'profile_picture' => $profilePicture,
                'password' => $password,
            ])
        );
    }

    /**
     * @param array<string, usersdata> $users
     */
    private function seedAuthUsers(array $users, string $password): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        foreach ($users as $user) {
            if (empty($user->email)) {
                continue;
            }

            $authUser = User::updateOrCreate(
                ['email' => $user->email],
                [
                    'name' => $user->fullname ?? 'Pet Finder User',
                    'password' => $password,
                ]
            );

            $this->authUserIdsByEmail[$user->email] = (int) $authUser->id;
        }
    }

    /**
     * @param array<string, usersdata> $users
     * @param array<string, pets> $petsByName
     */
    private function seedMessages(array $users, array $petsByName): void
    {
        $messages = array_merge([
            ['from' => 'sarah', 'to' => 'priya', 'pet' => 'Buddy', 'body' => 'Hi Priya, thanks for applying for Buddy!'],
            ['from' => 'priya', 'to' => 'sarah', 'pet' => 'Buddy', 'body' => 'Thank you! I can visit this weekend if that works.'],
            ['from' => 'sarah', 'to' => 'priya', 'pet' => 'Buddy', 'body' => 'Saturday at 11 AM would be perfect.'],
            ['from' => 'oliver', 'to' => 'lukas', 'pet' => 'Bentley', 'body' => 'Your application for Bentley is approved.'],
            ['from' => 'lukas', 'to' => 'oliver', 'pet' => 'Bentley', 'body' => 'Great news. Looking forward to meeting him!'],
            ['from' => 'anna', 'to' => 'michael', 'pet' => 'Nala', 'body' => 'Can you share your experience with rescue dogs?'],
            ['from' => 'michael', 'to' => 'anna', 'pet' => 'Nala', 'body' => 'Sure, I have adopted two rescues over the last 6 years.'],
            ['from' => 'rahul', 'to' => 'emma', 'pet' => 'Coco', 'body' => 'Coco is very active. Are you comfortable with daily activity needs?'],
            ['from' => 'emma', 'to' => 'rahul', 'pet' => 'Coco', 'body' => 'Yes, I go for long walks every evening and weekends.'],
        ], $this->extraMessages());

        foreach ($messages as $messageData) {
            $pet = $petsByName[$messageData['pet']] ?? null;
            if (!$pet) {
                continue;
            }

            Message::firstOrCreate(
                $this->pickExistingColumns('messages', [
                    'sender_id' => $users[$messageData['from']]->id,
                    'receiver_id' => $users[$messageData['to']]->id,
                    'pet_id' => $pet->id,
                    'body' => $messageData['body'],
                ]),
                $this->pickExistingColumns('messages', [
                    'read_at' => null,
                ])
            );
        }
    }

    /**
     * @param array<string, usersdata> $users
     */
    private function seedLostPetRequests(array $users): void
    {
        $requests = array_merge([
            [
                'owner' => 'nisha',
                'pet_name' => 'Tiger',
                'city' => 'Pune',
                'last_seen_area' => 'Kothrud, near City Pride',
                'description' => 'Brown indie dog, medium size, red collar, responds to the name Tiger.',
                'contact_phone' => '+919812345670',
                'photos' => ['https://images.unsplash.com/photo-1541364983171-a8ba01e95cfc?w=900&h=700&fit=crop'],
                'status' => 'open',
            ],
            [
                'owner' => 'sarah',
                'pet_name' => 'Maple',
                'city' => 'Austin',
                'last_seen_area' => 'South Congress Avenue',
                'description' => 'Golden retriever pup, very friendly, has blue harness.',
                'contact_phone' => '+12025550111',
                'photos' => ['https://images.unsplash.com/photo-1558788353-f76d92427f16?w=900&h=700&fit=crop'],
                'status' => 'open',
            ],
            [
                'owner' => 'anna',
                'pet_name' => 'Fritz',
                'city' => 'Berlin',
                'last_seen_area' => 'Tiergarten Park',
                'description' => 'Black-and-white mixed breed with yellow tag. Might be scared.',
                'contact_phone' => '+4915112345678',
                'photos' => ['https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=900&h=700&fit=crop'],
                'status' => 'open',
            ],
        ], $this->extraLostPetRequests());

        foreach ($requests as $requestData) {
            LostPetRequest::updateOrCreate(
                $this->pickExistingColumns('lost_pet_requests', [
                    'user_id' => $users[$requestData['owner']]->id,
                    'pet_name' => $requestData['pet_name'],
                    'city' => $requestData['city'],
                ]),
                $this->pickExistingColumns('lost_pet_requests', [
                    'last_seen_area' => $requestData['last_seen_area'],
                    'description' => $requestData['description'],
                    'contact_phone' => $requestData['contact_phone'],
                    'photos' => $requestData['photos'] ?? null,
                    'status' => $requestData['status'],
                ])
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function extraPetRecords(): array
    {
        return [
            ['name' => 'Simba', 'owner' => 'meera', 'breed' => 'Indie', 'age' => 3, 'gender' => 'Male', 'size' => 'medium', 'city' => 'Pune', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1583512603806-077998240c7a?w=800&h=1000&fit=crop', 'description' => 'Confident indie dog with balanced energy. Loves short hikes and is calm indoors after exercise.'],
            ['name' => 'Hazel', 'owner' => 'meera', 'breed' => 'Pug', 'age' => 4, 'gender' => 'Female', 'size' => 'small', 'city' => 'Pune', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1518020382113-a7e8fc38eac9?w=800&h=1000&fit=crop', 'description' => 'Sweet pug with a playful personality. Friendly with children and enjoys cuddles on the couch.'],
            ['name' => 'Bolt', 'owner' => 'arjun', 'breed' => 'Belgian Malinois', 'age' => 2, 'gender' => 'Male', 'size' => 'large', 'city' => 'Bengaluru', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1535294435445-d7249524ef2e?w=800&h=1000&fit=crop', 'description' => 'Highly intelligent and trainable. Best for an experienced adopter who can provide structure and activity.'],
            ['name' => 'Peanut', 'owner' => 'arjun', 'breed' => 'Shih Tzu', 'age' => 1, 'gender' => 'Female', 'size' => 'small', 'city' => 'Bengaluru', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1591946614720-90a587da4a36?w=800&h=1000&fit=crop', 'description' => 'Tiny and affectionate companion dog. Vaccinated and learning basic commands.'],
            ['name' => 'River', 'owner' => 'zoe', 'breed' => 'Australian Shepherd', 'age' => 3, 'gender' => 'Female', 'size' => 'medium', 'city' => 'Seattle', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1568572933382-74d440642117?w=800&h=1000&fit=crop', 'description' => 'Bright, energetic, and loves interactive toys. Good with active families and outdoor time.'],
            ['name' => 'Toby', 'owner' => 'zoe', 'breed' => 'Pomeranian', 'age' => 2, 'gender' => 'Male', 'size' => 'small', 'city' => 'Seattle', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1517423738875-5ce310acd3da?w=800&h=1000&fit=crop', 'description' => 'Cheerful pomeranian with lots of personality. House-trained and comfortable in apartment settings.'],
            ['name' => 'Willow', 'owner' => 'liam', 'breed' => 'Whippet', 'age' => 4, 'gender' => 'Female', 'size' => 'medium', 'city' => 'Brisbane', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=800&h=1000&fit=crop', 'description' => 'Quiet and gentle at home, fast and playful outdoors. Ideal for a calm household.'],
            ['name' => 'Oscar', 'owner' => 'liam', 'breed' => 'Staffordshire Bull Terrier', 'age' => 5, 'gender' => 'Male', 'size' => 'medium', 'city' => 'Brisbane', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=800&h=1000&fit=crop', 'description' => 'Loyal and affectionate dog who enjoys human company. Best with regular walks and enrichment games.'],
            ['name' => 'Snowy', 'owner' => 'emma', 'breed' => 'Samoyed', 'age' => 2, 'gender' => 'Female', 'size' => 'large', 'city' => 'Sydney', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=800&h=1000&fit=crop', 'description' => 'Fluffy and social, loves attention and moderate exercise. Grooming routine already established.'],
            ['name' => 'Rex', 'owner' => 'rahul', 'breed' => 'Doberman', 'age' => 3, 'gender' => 'Male', 'size' => 'large', 'city' => 'Mumbai', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?w=800&h=1000&fit=crop', 'description' => 'Protective but very affectionate with trusted people. Basic obedience complete and crate trained.'],
            ['name' => 'Sandy', 'owner' => 'daniel', 'breed' => 'Cavalier King Charles Spaniel', 'age' => 6, 'gender' => 'Female', 'size' => 'small', 'city' => 'Austin', 'adopted' => true, 'image_url' => 'https://images.unsplash.com/photo-1591769225440-811ad7d6eab3?w=800&h=1000&fit=crop', 'description' => 'Gentle senior dog with calm temperament. Recently adopted by a family with older children.'],
            ['name' => 'Nova', 'owner' => 'michael', 'breed' => 'German Shorthaired Pointer', 'age' => 2, 'gender' => 'Female', 'size' => 'large', 'city' => 'Seattle', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?w=800&h=1000&fit=crop', 'description' => 'Athletic and eager to learn. Perfect for adopters who enjoy running or long trails.'],
            ['name' => 'Chiku', 'owner' => 'priya', 'breed' => 'Indie Mix', 'age' => 1, 'gender' => 'Male', 'size' => 'small', 'city' => 'Pune', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1611003228941-98852ba62227?w=800&h=1000&fit=crop', 'description' => 'Young rescue pup, curious and affectionate. Doing well with socialization and leash confidence.'],
            ['name' => 'Panda', 'owner' => 'anna', 'breed' => 'Bernese Mix', 'age' => 3, 'gender' => 'Male', 'size' => 'large', 'city' => 'Berlin', 'adopted' => false, 'image_url' => 'https://images.unsplash.com/photo-1576201836106-db1758fd1c97?w=800&h=1000&fit=crop', 'description' => 'Big softie with friendly behavior and steady temperament. Comfortable around children and visitors.'],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function extraApplications(): array
    {
        return [
            ['applicant' => 'zoe', 'pet' => 'Bruno', 'status' => 'pending', 'description' => 'I have a spacious home and can provide regular exercise and training.'],
            ['applicant' => 'liam', 'pet' => 'River', 'status' => 'pending', 'description' => 'Looking for an active companion and I work remotely most days.'],
            ['applicant' => 'arjun', 'pet' => 'Poppy', 'status' => 'approved', 'description' => 'Already prepared with crate, vet references, and training plan.'],
            ['applicant' => 'meera', 'pet' => 'Nova', 'status' => 'pending', 'description' => 'I run daily and can provide both physical and mental stimulation.'],
            ['applicant' => 'emma', 'pet' => 'Willow', 'status' => 'pending', 'description' => 'Experienced with medium-energy breeds and can do weekly social sessions.'],
            ['applicant' => 'nisha', 'pet' => 'Peanut', 'status' => 'rejected', 'description' => 'Application declined due to mismatch in household schedule.'],
            ['applicant' => 'daniel', 'pet' => 'Simba', 'status' => 'pending', 'description' => 'I can provide an active routine and have prior rescue dog experience.'],
            ['applicant' => 'rahul', 'pet' => 'Snowy', 'status' => 'pending', 'description' => 'Ready for regular grooming needs and has a pet-friendly workplace option.'],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function extraMessages(): array
    {
        return [
            ['from' => 'arjun', 'to' => 'emma', 'pet' => 'Poppy', 'body' => 'Thanks for reviewing my application. I can schedule a meet this Friday.'],
            ['from' => 'emma', 'to' => 'arjun', 'pet' => 'Poppy', 'body' => 'Friday works. Please bring your ID and address proof for verification.'],
            ['from' => 'michael', 'to' => 'meera', 'pet' => 'Nova', 'body' => 'Nova needs two long walks daily. Are you comfortable with that routine?'],
            ['from' => 'meera', 'to' => 'michael', 'pet' => 'Nova', 'body' => 'Yes, I run every morning and can maintain an active routine consistently.'],
            ['from' => 'zoe', 'to' => 'nisha', 'pet' => 'Bruno', 'body' => 'Hi, is Bruno comfortable with other dogs at home?'],
            ['from' => 'nisha', 'to' => 'zoe', 'pet' => 'Bruno', 'body' => 'Yes, Bruno is friendly after a short introduction period.'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function extraLostPetRequests(): array
    {
        return [
            [
                'owner' => 'meera',
                'pet_name' => 'Golu',
                'city' => 'Pune',
                'last_seen_area' => 'Viman Nagar, near Phoenix Mall',
                'description' => 'White beagle, blue collar, slightly limping on rear leg.',
                'contact_phone' => '+919812345673',
                'photos' => ['https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?w=900&h=700&fit=crop'],
                'status' => 'open',
            ],
            [
                'owner' => 'zoe',
                'pet_name' => 'Skye',
                'city' => 'Seattle',
                'last_seen_area' => 'Green Lake trail',
                'description' => 'Gray husky mix wearing orange harness, friendly but nervous.',
                'contact_phone' => '+12025550114',
                'photos' => ['https://images.unsplash.com/photo-1516214104703-d870798883c5?w=900&h=700&fit=crop'],
                'status' => 'open',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function pickExistingColumns(string $table, array $payload): array
    {
        $allowedColumns = $this->getTableColumns($table);
        $filtered = [];

        foreach ($payload as $column => $value) {
            if (in_array($column, $allowedColumns, true)) {
                $filtered[$column] = $value;
            }
        }

        return $filtered;
    }

    /**
     * @return array<int, string>
     */
    private function getTableColumns(string $table): array
    {
        if (!array_key_exists($table, $this->tableColumnsCache)) {
            $this->tableColumnsCache[$table] = Schema::hasTable($table)
                ? Schema::getColumnListing($table)
                : [];
        }

        return $this->tableColumnsCache[$table];
    }

    private function getReferencedTable(string $table, string $column): ?string
    {
        $result = DB::selectOne(
            'SELECT referenced_table_name
             FROM information_schema.key_column_usage
             WHERE table_schema = DATABASE()
               AND table_name = ?
               AND column_name = ?
               AND referenced_table_name IS NOT NULL
             LIMIT 1',
            [$table, $column]
        );

        if (!$result || empty($result->referenced_table_name)) {
            return null;
        }

        return (string) $result->referenced_table_name;
    }
}
