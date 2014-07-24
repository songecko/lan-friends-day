<?php


namespace Odiseo\LanBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
	
	public function lastUserWhoTweets(){
	
	
		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('Odiseo\LanBundle\Entity\TwitterUser', 't');
		$rsm->addFieldResult('t', 'id', 'id');
		$rsm->addScalarResult('user_id', 'userId');
		
		$query = $this->_em->createNativeQuery('select id,user_id , max(created_at) as max from fos_twitter_user a  group by user_id  order by max desc limit 0,18 ', $rsm);
		$twitUsers = $query->getResult();
		return $twitUsers;
	
	/*
		$queryBuilder = $this->getCollectionQueryBuilder();
		$queryBuilder->expr()->max($this->getAlias().'.created_at');
		$queryBuilder->groupBy($this->getAlias().'.user');
		
		
		
		
		
		
		$queryBuilder->setMaxResults(19);
		return $queryBuilder->getQuery()->getResult();
		*/
	}
	
}