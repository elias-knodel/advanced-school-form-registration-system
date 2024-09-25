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
        $userAdmin = UserFactory::new()->asAdmin()->create();

        // Unauthenticated user can access empty user collection
        $this->browser()
            ->get('/api/users')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 0);

        // Authenticated user can access user collection with only self
        $this->browser()
            ->actingAs($user)
            ->get('/api/users')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 1);

        // Authenticated admin user can access user collection with all users
        $this->browser()
            ->actingAs($userAdmin)
            ->get('/api/users')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 2)
            ->assertJsonMatches('"hydra:member"', [
                [
                    '@id' => '/api/users/' . $user->getId(),
                    '@type' => 'User',
                    'email' => $user->getEmail(),
                ],
                [
                    '@id' => '/api/users/' . $userAdmin->getId(),
                    '@type' => 'User',
                    'email' => $userAdmin->getEmail(),
                ],
            ]);
    }

    public function testGetUser()
    {
        $user = UserFactory::createOne();
        $userExternal = UserFactory::createOne();

        // Unauthenticated user cannot access user
        $this->browser()
            ->get('/api/users/' . $user->getId())
            ->assertStatus(401);

        // Authenticated user cannot access other user
        $this->browser()
            ->actingAs($user)
            ->get('/api/users/' . $userExternal->getId())
            ->assertStatus(403);

        // Authenticated user can access own user
        $this->browser()
            ->actingAs($user)
            ->get('/api/users/' . $user->getId())
            ->assertStatus(200)
            ->assertJson();
    }

    public function testPatchUser()
    {
        $user = UserFactory::createOne();
        $user2 = UserFactory::createOne();

        // Unauthenticated user cannot update user
        $this->browser()
            ->patch('/api/users/' . $user->getId(), [
                'json' => [
                    'email' => 'changed@example.com',
                ],
                'headers' => ['Content-Type' => 'application/merge-patch+json']
            ])
            ->assertStatus(401);

        // Authenticated user cannot update other user
        $this->browser()
            ->actingAs($user)
            ->patch('/api/users/' . $user2->getId(), [
                'json' => [
                    'email' => 'changed@example.com',
                ],
                'headers' => ['Content-Type' => 'application/merge-patch+json']
            ])
            ->assertStatus(403);

        // Authenticated user can update own user
        $this->browser()
            ->actingAs($user)
            ->patch('/api/users/' . $user->getId(), [
                'json' => [
                    'email' => 'changed@example.com',
                ],
                'headers' => ['Content-Type' => 'application/merge-patch+json']
            ])
            ->assertStatus(200);
    }

    public function testDeleteUser()
    {
        $user = UserFactory::createOne();
        $user2 = UserFactory::createOne();

        // Unauthenticated user cannot delete user
        $this->browser()
            ->delete('/api/users/' . $user->getId())
            ->assertStatus(401)
            ->assertJson();

        // Authenticated user cannot delete other user
        $this->browser()
            ->actingAs($user)
            ->delete('/api/users/' . $user2->getId())
            ->assertStatus(403)
            ->assertJson();

        // Authenticated user can delete own user
        $this->browser()
            ->actingAs($user)
            ->delete('/api/users/' . $user->getId())
            ->assertStatus(204);
    }
}
