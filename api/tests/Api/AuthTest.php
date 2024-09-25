<?php

namespace App\Tests\Api;

use App\Tests\Functional\CustomApiTestCase;
use App\User\Factory\UserFactory;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthTest extends CustomApiTestCase
{
    use ResetDatabase;
    use Factories;
    use VarDumperTestTrait;

    /**
     * @throws \JsonException
     */
    public function testUserApiJWTLogin()
    {
        $user = UserFactory::createOne(
            [
                'email' => 'admin@example.com',
                'password' => 'StrongPassword123!?',
            ]
        );

        $this->browser()
            ->get('/api/users/' . $user->getId())
            ->assertStatus(401);

        $options = [
            'json' => [
                'email' => $user->getUserIdentifier(),
                'password' => 'StrongPassword123!?',
            ],
        ];

        $this->browser()
            ->post('/api/auth', $options)
            ->assertStatus(200)
            ->assertJson();

        $token = $this->browser()
            ->post('/api/auth', $options)
            ->assertStatus(200)
            ->assertJson()
            ->json()
            ->decoded();

        // Check that jwt is returned
        $jwtToken = $token['token'];
        $this->assertNotEmpty($jwtToken);

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
            ],
        ];

        $this->browser()
            ->get('/api/users/' . $user->getId(), $options)
            ->assertStatus(200);
    }
}
