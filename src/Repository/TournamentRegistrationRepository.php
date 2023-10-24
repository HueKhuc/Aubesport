<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TournamentRegistration;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<TournamentRegistration>
 */
class TournamentRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentRegistration::class);
    }

    /**
    * @return TournamentRegistration[]
    */
    public function findByUserUuid(string $uuid): array
    {
        $qb = $this->createQueryBuilder('tournamentRegistration')
            ->select('tournamentRegistration')
            ->innerJoin('App\Entity\User', 'user', 'WITH', 'user = tournamentRegistration.user')
            ->where('user.uuid = :userUuid')
            ->setParameter('userUuid', $uuid)
            ->orderBy('tournamentRegistration.createdAt', 'DESC');

        $query = $qb->getQuery();

        $results = (array) $query->getResult();

        // if (count($results) === 0) {
        //     return [];
        // }

        // foreach ($results as $result) {
        //     assert($result instanceof TournamentRegistration);
        // }

        return $results;

    }
}
