<?php

namespace App\Tests\Api;

use App\Tests\Functional\CustomApiTestCase;
use App\User\Entity\User;
use App\User\Factory\UserFactory;
use App\User\Repository\UserRepository;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserTest extends CustomApiTestCase
{
    use ResetDatabase;
    use Factories;

    public function testCreateUser()
    {
        $email = 'newuser@example.de';
        $password = 'strong_password_789';

        $this->browser()
            ->post('/api/users', [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ])
            ->assertJson()
            ->assertStatus(201);

        // Now check that password hasher and identifier are working correctly

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => 'newuser@example.de']);

        assert($user instanceof User);
        assert($user->getUserIdentifier() === $email);
        assert($user->getPassword() !== $password);
    }

    public function testCreateUserInvalidEmail()
    {
        $this->browser()
            ->post('/api/users', [
                'json' => [
                    'email' => 'newuser@.de',
                    'password' => 'strong_password_789',
                ],
            ])
            ->assertJson()
            ->assertStatus(422)
            ->assertJsonMatches(
                '"@type"', 'ConstraintViolationList'
            )
            ->assertJsonMatches(
                'violations', [
                    [
                        'propertyPath' => 'email',
                        'message' => 'This value is not a valid email address.',
                        'code' => 'bd79c0ab-ddba-46cc-a703-a7a4b08de310',
                    ],
                ]
            );
    }

    public function testCreateUserUnsecurePassword()
    {
        $this->browser()
            ->post('/api/users', [
                'json' => [
                    'email' => 'newuser@example.de',
                    'password' => 'strong_password',
                ],
            ])
            ->assertJson()
            ->assertStatus(422)
            ->assertJsonMatches('violations', [
                [
                    "propertyPath" => "password",
                    "message" => "The password strength is too low. Please use a stronger password.",
                    "code" => "4234df00-45dd-49a4-b303-a75dbf8b10d8"
                ]
            ]);
    }

    public function testGetUserCollection()
    {
        $user = UserFactory::createOne();

        $this->browser()
            ->get('/api/users')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 1)
            ->assertJsonMatches('"hydra:member"', [
                [
                    '@id' => '/api/users/' . $user->getId(),
                    '@type' => 'User',
                    'email' => $user->getEmail(),
                ],
            ]);
    }

//    public function testGetUserCollection(): void
//    {
//        // Arrange: Create some test users
//        $client = static::createClient();
//        $client->request('POST', '/users', [
//            'json' => [
//                'email' => 'test1@example.com',
//                'password' => 'strong_password_123',
//            ],
//        ]);
//
//        $client->request('POST', '/users', [
//            'json' => [
//                'email' => 'test2@example.com',
//                'password' => 'strong_password_456',
//            ],
//        ]);
//
//        // Act: Make a GET request to the collection endpoint
//        $response = $client->request('GET', '/users');
//
//        // Assert: Verify the status code and response structure
//        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains([
//            ['email' => 'test1@example.com'],
//            ['email' => 'test2@example.com'],
//        ]);
//    }

//    public function testGetUser(): void
//    {
//        // Arrange: Create a test user
//        $client = static::createClient();
//        $client->request('POST', '/users', [
//            'json' => [
//                'email' => 'test@example.com',
//                'password' => 'ChangeMe123!?=',
//            ],
//        ]);
//
//        $userIri = $this->findIriBy(User::class, ['email' => 'test@example.com']);
//
//        // Act: Make a GET request to retrieve the user
//        $response = $client->request('GET', $userIri);
//
//        // Assert: Verify the response
//        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains(['email' => 'test@example.com']);
//    }

//    public function testPatchToUpdateUser(): void
//    {
//        UserFactory::createMany(10);
//
//        $this->browser()
//            ->get('/api/users')
//            ->assertJson()
//            ->assertJsonMatches('"hydra:totalItems"', 10);
//    }

//    public function testDeleteUser(): void
//    {
//        // Arrange: Create a test user
//        $client = static::createClient();
//        $client->request('POST', '/users', [
//            'json' => [
//                'email' => 'deleteuser@example.com',
//                'password' => 'password_to_delete',
//            ],
//        ]);
//
//        $userIri = $this->findIriBy(User::class, ['email' => 'deleteuser@example.com']);
//
//        // Act: Delete the user
//        $client->request('DELETE', $userIri);
//
//        // Assert: Check that the user was deleted successfully
//        $this->assertResponseStatusCodeSame(204);
//
//        // Verify that the user no longer exists
//        $client->request('GET', $userIri);
//        $this->assertResponseStatusCodeSame(404);
//    }
}
