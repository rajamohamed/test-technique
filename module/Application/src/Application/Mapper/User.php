<?php
namespace Application\Mapper;

use Doctrine\ORM\EntityRepository;


class User extends EntityRepository
{

	/*public function findOneWithProfileById($id)
    {
        $q = $this->createQueryBuilder('us')
			->addSelect('pr')
            ->innerJoin('us.profile', 'pr')
            ->where('us.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $q->getSingleResult();
    }*/
	
	public function findAll()
    {
        $q = $this->createQueryBuilder('us')
			->addSelect('pr')
            ->innerJoin('us.profile', 'pr')
            ->getQuery();

        return $q->getArrayResult();
    }

    public function findAllOrderByUser($column, $order)
    {
        $q = $this->createQueryBuilder('us')
            ->addSelect('pr')
            ->innerJoin('us.profile', 'pr')
            ->orderBy('us.'.$column, $order)
            ->getQuery();

        return $q->getArrayResult();
    }

     public function findAllOrderByProfile($column, $order)
    {
        $q = $this->createQueryBuilder('us')
            ->addSelect('pr')
            ->innerJoin('us.profile', 'pr')
            ->orderBy('pr.'.$column, $order)
            ->getQuery();

        return $q->getArrayResult();
    }
}