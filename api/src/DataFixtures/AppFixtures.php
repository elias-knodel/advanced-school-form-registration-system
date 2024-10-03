<?php

namespace App\DataFixtures;

use App\User\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'string',
            'password' => 'string',
            'roles' => ['ROLE_ADMIN'],
        ]);

//        $school = new School();
//        $manager->persist($school);
//
//        $customField1 = new CustomField();
//        $customField1->setTitle('Name');
//        $customField1->setType(CustomFieldType::TEXT);
//        $customField1->setSchool($school);
//        $manager->persist($customField1);
//
//        $customField12 = new CustomField();
//        $customField12->setTitle('Surname');
//        $customField12->setType(CustomFieldType::TEXTAREA);
//        $customField12->setSchool($school);
//        $manager->persist($customField12);
//
//        $customField123 = new CustomField();
//        $customField123->setTitle('Birthday');
//        $customField123->setType(CustomFieldType::DATE);
//        $customField123->setSchool($school);
//        $manager->persist($customField123);
//
//        $customForm1 = new CustomForm();
//        $customForm1->setTitle('Register Phase 1');
//        $customForm1->setSchool($school);
//        $manager->persist($customForm1);
//
//        $customForm12 = new CustomForm();
//        $customForm12->setTitle('Register Phase 2');
//        $customForm12->setSchool($school);
//        $manager->persist($customForm12);
//
//        $customFormField1 = new CustomFormField();
//        $customFormField1->setField($customField1);
//        $customFormField1->setForm($customForm1);
//        $customFormField1->setPosition(1);
//        $manager->persist($customFormField1);
//
//        $customFormField123 = new CustomFormField();
//        $customFormField123->setField($customField12);
//        $customFormField123->setForm($customForm1);
//        $customFormField123->setPosition(1);
//        $manager->persist($customFormField123);
//
//        $customFormField124 = new CustomFormField();
//        $customFormField124->setField($customField12);
//        $customFormField124->setForm($customForm12);
//        $customFormField124->setPosition(2);
//        $manager->persist($customFormField124);
//
//        $customFormField125 = new CustomFormField();
//        $customFormField125->setField($customField123);
//        $customFormField125->setForm($customForm12);
//        $customFormField125->setPosition(1);
//        $manager->persist($customFormField125);

//        SchoolFactory::createMany(10);
//        CustomFieldFactory::createMany(10);
//        CustomFormFactory::createMany(10);
//        CustomFormFieldFactory::createMany(10);
//        SchoolRegisterRequestFactory::createMany(10);

        $manager->flush();
    }
}
