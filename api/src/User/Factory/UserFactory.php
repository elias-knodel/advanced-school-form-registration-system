<?php

namespace App\User\Factory;

use App\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return User::class;
    }

    public function withRoles(array $roles): self
    {
        return $this->with(['roles' => $roles]);
    }

    public function asAdmin(): self
    {
        return $this->withRoles(['ROLE_ADMIN']);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'password' => self::faker()->password(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (User $user): void {
                $user->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                ));
            });
    }
}
