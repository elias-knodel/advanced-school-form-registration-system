<?php

namespace App\School\Factory;

use App\School\Entity\SchoolStaff;
use App\School\Enum\SchoolStaffRole;
use App\User\Factory\UserFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<SchoolStaff>
 */
final class SchoolStaffFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return SchoolStaff::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'role' => SchoolStaffRole::STAFF,
            'employee' => UserFactory::createOne(),
            'school' => SchoolFactory::createOne(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
//             ->afterInstantiate(function(SchoolStaff $schoolStaff): void {})
        ;
    }
}
