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

    public function findByAge($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.age > ?1')
            ->setParameter(1, $value)
            ->getQuery()
            ->getResult();
    }

    public function findByAge02($value)
    {
        $arr = explode(' ', $value);
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->gte('p.age', '?1'))
            ->andWhere($builder->expr()->lte('p.age', '?2'))
            ->setParameters(array(1 => $arr[0], 2 => $arr[1]))
            ->getQuery()
            ->getResult();
    }

    public function findByName02($value)
    {
        $arr = explode(',', $value);// 『,』で区切った複数検索
        return $this->createQueryBuilder('p')
            ->where("p.name in (?1, ?2)")
            ->setParameters([
                1 => $arr[0], 2 => $arr[1]])
            ->getQuery()
            ->getResult();
    }

    public function findByName03($value)//exprを使った検索
    {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->eq('p.name', '?1'))//eqが等しいという意味になる。
            ->setParameter(1, $value)
            ->getQuery()
            ->getResult();
    }

    public function findByName04($value)
    {
        $arr = explode(' ', $value);
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->in('p.name', $arr)) //inでは直説配列を指定できる。
            ->getQuery()
            ->getResult();
    }

    public function findByNameOrMail($value)//名前かemailのどちらかでヒットしたもの
    {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->like('p.name', '?1'))
            ->orWhere($builder->expr()->like('p.mail', '?2'))
            ->setParameters(
                [
                    1 => '%' . $value . '%',
                    2 => '%' . $value . '%'
                ])
            ->getQuery()
            ->getResult();
    }

    public function findAllwithSort()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.age', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
