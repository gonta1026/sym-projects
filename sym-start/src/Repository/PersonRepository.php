<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }
    //下記からは追加したメソッドです。
    // public function findByName($value)
    // {
    //     var_dump($value);
    //     return $this->createQueryBuilder('p')//  "p"はエイリアス、テーブルが指定されている。
    //         ->where('p.name = ?1')
    //         ->setParameter(1, $value)
    //         ->getQuery()
    //         ->getResult();
    // }
}
