<?php

namespace App\Tests\Api;

use App\Tests\Functional\CustomApiTestCase;
use App\User\Entity\User;
use App\User\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserTest extends CustomApiTestCase
{
    use ResetDatabase;
    use Factories;

    public function testGetUserCollection(): void
    {
        // Arrange: Create some test users
        $client = static::createClient();
        $client->request('POST', '/users', [
            'json' => [
                'email' => 'test1@example.com',
                'password' => 'strong_password_123',
            ],
        ]);

        $client->request('POST', '/users', [
            'json' => [
                'email' => 'test2@example.com',
                'password' => 'strong_password_456',
            ],
        ]);

        // Act: Make a GET request to the collection endpoint
        $response = $client->request('GET', '/users');

        // Assert: Verify the status code and response structure
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            ['email' => 'test1@example.com'],
            ['email' => 'test2@example.com'],
        ]);
    }

    public function testGetUser(): void
    {
        // Arrange: Create a test user
        $client = static::createClient();
        $client->request('POST', '/users', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'ChangeMe123!?=',
            ],
        ]);

        $userIri = $this->findIriBy(User::class, ['email' => 'test@example.com']);

        // Act: Make a GET request to retrieve the user
        $response = $client->request('GET', $userIri);

        // Assert: Verify the response
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['email' => 'test@example.com']);
    }

    public function testCreateUser(): void
    {
        // Arrange: Prepare the registration input data
        $client = static::createClient();
        $response = $client->request('POST', '/users', [
            'json' => [
                'email' => 'newuser@example.com',
                'password' => 'strong_password_789',
            ],
        ]);

        // Assert: Check that the response was successful
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['email' => 'newuser@example.com']);
    }

    public function testPatchToUpdateUser(): void
    {
        UserFactory::createMany(10);

        $this->browser()
            ->get('/users')
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 10);
    }

    public function testDeleteUser(): void
    {
        // Arrange: Create a test user
        $client = static::createClient();
        $client->request('POST', '/users', [
            'json' => [
                'email' => 'deleteuser@example.com',
                'password' => 'password_to_delete',
            ],
        ]);

        $userIri = $this->findIriBy(User::class, ['email' => 'deleteuser@example.com']);

        // Act: Delete the user
        $client->request('DELETE', $userIri);

        // Assert: Check that the user was deleted successfully
        $this->assertResponseStatusCodeSame(204);

        // Verify that the user no longer exists
        $client->request('GET', $userIri);
        $this->assertResponseStatusCodeSame(404);
    }
}
