<?php

namespace App\Tests\Api;

use App\Tests\Functional\CustomApiTestCase;
use App\User\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class SchoolTest extends CustomApiTestCase
{
    use ResetDatabase;
    use Factories;

    public function testCreateSchool()
    {
        $user = UserFactory::createOne();
        $userVerified = UserFactory::new()->asAdmin()->create();

        // Unauthorized user cannot create school
        $this->browser()
            ->post('/api/schools', [
                'json' => [],
            ])
            ->assertStatus(401);

        // Authorized user cannot create school
        $this->browser()
            ->actingAs($user)
            ->post('/api/schools', [
                'json' => [],
            ])
            ->assertStatus(403);

        // Authorized but verified user can create school
        $this->browser()
            ->actingAs($userVerified)
            ->post('/api/schools', [
                'json' => [],
            ])
            ->assertStatus(201)
            ->assertJson()
            ->assertJsonMatches('"@type"', 'School');
    }
//
//    public function testGetSchoolStaffCollection()
//    {
//    }
//
//    public function testGetSchoolStaff()
//    {
//        $user = UserFactory::createOne();
//        $school = SchoolFactory::createOne();
//
//        $this->browser()
//            ->get( "/api/schools")
//            ->assertStatus(200)
//            ->assertJson()
//            ->assertJsonMatches('"hydra:totalItems"', 1);

//        $schoolStaff = SchoolStaffFactory::new([
//            'school' => $school,
//            'employee' => $user,
//        ])->create();

//        $this->browser()
//            ->get("/api/schools/{$school->getId()}/staff/{$schoolStaff->getId()}")
//            ->assertJson()
//            ->assertStatus(200);

    // Unauthenticated user cannot get school
//    }
//
//    public function testPatchSchoolStaff()
//    {
//    }
//
//    public function testDeleteSchoolStaff()
//    {
//    }
}
