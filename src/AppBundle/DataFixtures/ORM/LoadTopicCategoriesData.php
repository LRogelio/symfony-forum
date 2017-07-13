<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\TopicCategory;

class LoadTopicCategoriesData extends AbstractBaseFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$this->truncate($manager, 'topic_category');

		$data = $this->getData();

		foreach ($data as $key => $item)
		{
			$category = new TopicCategory();
			$category
				->setName($item['name'])
				->setDescription($item['description']);

			$this->addReference('category-' . $key, $category);

			$manager->persist($category);
		}

		$manager->flush();
	}

	protected function getData()
	{
		return [
			'html' => [
				'name' => 'HTML & CSS',
				'description' => 'Anything related to HTML, CSS and preprocessors like SASS'
			],
			'js' => [
				'name' => 'Javascript',
				'description' => 'All about javascript'
			],
			'php' => [
				'name' => 'PHP & MYSQL',
				'description' => 'Stuff related to PHP, Servers, Mysql and CMS'
			],
			'seo' => [
				'name' => 'SEO & Marketing',
				'description' => 'SEO, marketing and legal help'
			],
			'hosting' => [
				'name' => 'Hosting',
				'description' => 'Help about hosting'
			]
		];
	}

	public function getOrder()
	{
		return 2;
	}
}