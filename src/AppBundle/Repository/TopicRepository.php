<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * TopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TopicRepository extends \Doctrine\ORM\EntityRepository
{
	public function getTopics($categoryId, $offset = 0, $limit = null)
	{
		$query = $this->createQueryBuilder('t')
	        ->where('t.categoryId = :categoryId')
	        ->orderBy('t.createdAt', 'DESC')
	        ->setParameter('categoryId', $categoryId)
	        ->getQuery();

		$query->setFirstResult($offset);

		if ($limit) {
			$query->setMaxResults($limit);
		}

		$paginator = new Paginator($query);
		$paginator->setUseOutputWalkers(false);

		return $paginator;
	}

	public function getTopicsByUser($userId)
	{
		$query = $this->createQueryBuilder('t')
			->where('t.userId = :userId')
			->orderBy('t.createdAt', 'DESC')
			->setParameter('userId', $userId)
			->getQuery();

		return $query->getResult();
	}
}