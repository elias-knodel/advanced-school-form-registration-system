<?php

namespace App\DataFixtures;

use App\School\Entity\CustomField;
use App\School\Entity\CustomForm;
use App\School\Entity\CustomFormField;
use App\School\Entity\School;
use App\School\Factory\CustomFieldFactory;
use App\School\Factory\CustomFormFactory;
use App\School\Factory\CustomFormFieldFactory;
use App\School\Factory\SchoolFactory;
use App\School\Factory\SchoolRegisterRequestFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $school = new School();
        $manager->persist($school);

        $customField1 = new CustomField();
        $customField1->setSchool($school);
        $manager->persist($customField1);

        $customField12 = new CustomField();
        $customField12->setSchool($school);
        $manager->persist($customField12);

        $customField123 = new CustomField();
        $customField123->setSchool($school);
        $manager->persist($customField123);

        $customForm1 = new CustomForm();
        $customForm1->setSchool($school);
        $manager->persist($customForm1);

        $customForm12 = new CustomForm();
        $customForm12->setSchool($school);
        $manager->persist($customForm12);

        $customFormField1 = new CustomFormField();
        $customFormField1->setField($customField1);
        $customFormField1->setForm($customForm1);
        $manager->persist($customFormField1);

        $customFormField12 = new CustomFormField();
        $customFormField12->setField($customField12);
        $customFormField12->setForm($customForm1);
        $manager->persist($customFormField12);

        $customFormField12 = new CustomFormField();
        $customFormField12->setField($customField12);
        $customFormField12->setForm($customForm12);
        $manager->persist($customFormField12);

        $customFormField12 = new CustomFormField();
        $customFormField12->setField($customField123);
        $customFormField12->setForm($customForm12);
        $manager->persist($customFormField12);

//        SchoolFactory::createMany(10);
//        CustomFieldFactory::createMany(10);
//        CustomFormFactory::createMany(10);
//        CustomFormFieldFactory::createMany(10);
//        SchoolRegisterRequestFactory::createMany(10);

        $manager->flush();
    }
}
