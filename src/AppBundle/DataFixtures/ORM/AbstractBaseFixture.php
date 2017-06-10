<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

abstract class AbstractBaseFixture extends AbstractFixture
{
	public function truncate(ObjectManager $manager, $tablename)
	{
		$sql = 'SET foreign_key_checks = 0;TRUNCATE %s;SET foreign_key_checks = 1;';
		$c = $manager->getConnection()->exec(sprintf($sql, $tablename));
	}
}