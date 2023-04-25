<?php

namespace App\Repository;

use App\Entity\CustomerLogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerLogin>
 *
 * @method CustomerLogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerLogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerLogin[]    findAll()
 * @method CustomerLogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerLoginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerLogin::class);
    }
}
