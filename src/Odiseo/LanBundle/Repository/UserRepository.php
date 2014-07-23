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
    	$queryBuilder = $this->getCollectionQueryBuilder();
    	$queryBuilder->innerJoin($this->getAlias().'.twitters', 't');
    	$queryBuilder->groupBy($this->getAlias().'.id');
    	$queryBuilder->orderBy('t.createdAt','DESC');
    	$queryBuilder->setMaxResults(19);
    	return $queryBuilder->getQuery()->getResult();
    }
    

}