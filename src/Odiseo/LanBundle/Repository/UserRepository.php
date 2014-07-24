<?php

namespace Odiseo\LanBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function createPaginator(array $criteria = null, array $orderBy = null)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();
        $queryBuilder->leftJoin($this->getAlias().'.twitters', 't');
        $queryBuilder->orderBy('t.createdAt','DESC');
        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $orderBy);
        return $this->getPaginator($queryBuilder);
    }

    public function lastUserWhoTweets(){
    	$queryBuilder = $this->createQueryBuilder($this->getAlias());
    	$queryBuilder->select($this->getAlias().', MAX(t.createdAt) AS max_date');
    	$queryBuilder->leftJoin($this->getAlias().'.twitters', 't');
    	$queryBuilder->andWhere($this->getAlias().'.dni IS NOT NULL');
    	$queryBuilder->groupBy('t.user');
    	$queryBuilder->orderBy('max_date','DESC');
    	$queryBuilder->setMaxResults(19);
    	
    	return $queryBuilder->getQuery()->getResult();
    }
    
    public function getRegisteredUsers()
    {
    	$queryBuilder = $this->getCollectionQueryBuilder();
    	$queryBuilder->where($this->getAlias().'.dni IS NOT NULl');
    
    	return $queryBuilder->getQuery()->getResult();
    }
}