<?php

namespace App\User\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\User\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class UserIsSecureExtension implements QueryCollectionExtensionInterface
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($resourceClass !== User::class) {
            return;
        }

        if ($this->security->getUser() === null) {
            $queryBuilder->andWhere('1 = 0');
            return;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.id = :userId', $rootAlias));
        $queryBuilder->setParameter('userId', $this->security->getUser()->getId());
    }
}
