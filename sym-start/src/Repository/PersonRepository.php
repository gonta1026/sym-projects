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
    //曖昧検索
    public function findByName($value)
    {
        return $this->createQueryBuilder('p')//  "p"はエイリアス、テーブルが指定されている。
            ->where('p.name like ?1')// = の代わりlikeを使う
            ->setParameter(1, '%' . $value . '%')//　%でワイルドカード指定をしている
            ->getQuery()//Queryクラスのインスタンスを取得
            ->getResult();//queryの実行結果を返す。
    }
}
