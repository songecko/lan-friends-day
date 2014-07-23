<?php


namespace Odiseo\LanBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class TwitterUserRepository extends EntityRepository
{
   
	
	/**
	 * return an array with a number of 'max_size_Result' TwitterUser records.
	 * @param unknown $maxSizeResult
	 * @return array
	 */
	public function findLastTweets( $maxSizeResult ){
		
		$queryBuilder = $this->getCollectionQueryBuilder();
		$queryBuilder->leftJoin($this->getAlias().'.user', 'u');
		$queryBuilder->orderBy($this->getAlias().'.createdAt','DESC');
		$queryBuilder->setMaxResults($maxSizeResult);
		return $queryBuilder->getQuery()->getResult();
	}
	
}