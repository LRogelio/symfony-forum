<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * TopicCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TopicCategoryRepository extends \Doctrine\ORM\EntityRepository
{
	public function getCategoriesWithLastComment()
	{
		$joinQuery = $this->_em->createQueryBuilder()
			->select('MAX(c2.createdAt)')
			->from('AppBundle:TopicComment', 'c2')
			->leftJoin('AppBundle:Topic', 't2', Join::LEFT_JOIN, 't2.id = c2.topicId')
			->where('t.categoryId = t2.categoryId')
			->getQuery();

		$query = $this->createQueryBuilder('cat')
			->select('cat.id,cat.name,cat.description, t.id as topicId, t.title as topicTitle, cm.createdAt as commentCreatedAt')
			->leftJoin('AppBundle:Topic', 't', Join::LEFT_JOIN, 't.categoryId = cat.id')
			->leftJoin('AppBundle:TopicComment', 'cm', Join::LEFT_JOIN, 'cm.topicId = t.id AND cm.createdAt = ('.$joinQuery->getDQL().')')
			->groupBy('cat.id')
			->getQuery();

		return $query->getArrayResult();
	}
}