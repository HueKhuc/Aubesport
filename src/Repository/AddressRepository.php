<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<AddressRepository>
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function findByUserUuid(string $uuid): ?Address
    {
        $qb = $this ->createQueryBuilder('address')
            ->innerJoin('App\Entity\User', 'user', 'WITH', 'address = user.address')
            ->where('user.uuid = :userUuid')
            ->setParameters([
                'userUuid' => $uuid
            ]);

        $query = $qb->getQuery();

        if ($query->getOneOrNullResult() === null) {
            return null;
        }

        assert($query->getOneOrNullResult() instanceof Address);

        return $query->getOneOrNullResult();
    }
}
