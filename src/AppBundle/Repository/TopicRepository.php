<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * TopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TopicRepository extends \Doctrine\ORM\EntityRepository
{
	public function getTopicsWithLastComment($categoryId)
	{
		$countField = 'SELECT COUNT(c3) FROM AppBundle:TopicComment c3 WHERE c3.topicId=t.id';
		$dateSubquery = 'SELECT MAX(c2.createdAt) FROM AppBundle:TopicComment c2 WHERE c2.topicId=t.id';

		$query = $this->createQueryBuilder('t')
			->select('t.id ,t.title, t.createdAt, c.id AS commentId, c.createdAt AS commentCreatedAt, ('.$countField.') AS totalComments')
			->leftJoin('AppBundle:TopicComment', 'c', Join::LEFT_JOIN, "c.topicId = t.id AND c.createdAt = (".$dateSubquery.")")
			->where('t.categoryId = :categoryId')
			->orderBy('t.createdAt', 'DESC')
			->setParameter('categoryId', $categoryId)
			->getQuery();

		return $query->getArrayResult();
	}
}