<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Topic;

class LoadTopicsData extends AbstractBaseFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$this->truncate($manager, 'topic');

		$data = $this->getData();

		$user = $this->getReference('user-js-sensei');

		foreach ($data as $key => $item) {
			$category = $this->getReference('category-' . $item['category']);

			$topic = (new Topic())
				->setTitle($item['title'])
				->setCategory($category)
				->setUser($user)
				->setUpdatedAt((new \DateTime()))
				->setCreatedAt((new \DateTime()));

			if (isset($item['created_at'])) {
				$topic
					->setCreatedAt($item['created_at'])
					->setUpdatedAt($item['created_at']);
			}

			if (isset($item['updated_at'])) {
				$topic->setUpdatedAt($item['updated_at']);
			}

			$this->addReference('topic-' . $key, $topic);

			$manager->persist($topic);
		}

		$manager->flush();
	}

	protected function getData()
	{
		return [
			'css_ie' => [
				'title' => 'css not working in IE6',
				'category' => 'html',
				'created_at' => new \DateTime('2016-09-10 10:00:05')
			],
			'css_html5' => [
				'title' => 'How to migrate old site to HTML5',
				'category' => 'html',
				'created_at' => new \DateTime('2016-08-20 22:00:05')
			],
			'css_framework' => [
				'title' => 'Which css framework do you use? Bootstrap, Foundation or other',
				'category' => 'html',
				'created_at' => new \DateTime('2017-01-10 14:00:05')
			],
			'css_sass' => [
				'title' => 'Error compiling sass in node!!!',
				'category' => 'html',
				'created_at' => new \DateTime('2016-12-10 10:00:05')
			],
			'js_jquery' => [
				'title' => 'Bind events in html from AJAX',
				'category' => 'js',
				'created_at' => new \DateTime('2016-11-27 10:00:05')
			],
			'js_node' => [
				'title' => 'Any example how to setup node in wordpress',
				'category' => 'js',
				'created_at' => new \DateTime('2016-07-09 10:00:05')
			],
			'js_oop' => [
				'title' => 'How can I build classes??',
				'category' => 'js',
				'created_at' => new \DateTime('2017-02-25 10:00:05')
			],
			'js_es6' => [
				'title' => 'I need a ES6 compiler!!!',
				'category' => 'js',
				'created_at' => new \DateTime('2017-03-14 10:00:05')
			],
			'js_node_wp' => [
				'title' => 'Any example how to setup node in wordpress theme',
				'category' => 'js',
				'created_at' => new \DateTime('2016-05-13 00:00:05')
			],
			'js_angular' => [
				'title' => 'migrate angular 1 to 2',
				'category' => 'js',
				'created_at' => new \DateTime('2016-10-10 10:00:05')
			],
			'php_vm' => [
				'title' => 'Recommend a vagrant box for local development',
				'category' => 'php',
				'created_at' => new \DateTime('2017-08-28 13:00:05')
			],
			'php_laravel' => [
				'title' => 'is possible use laravel in shared hosting?',
				'category' => 'php',
				'created_at' => new \DateTime('2016-06-20 09:00:05')
			],
			'php_mysql' => [
				'title' => 'error in field collation iso89',
				'category' => 'php',
				'created_at' => new \DateTime('2017-02-11 10:00:05')
			],
			'php_tutorial' => [
				'title' => 'I need tutorial/book/course to start php',
				'category' => 'php',
				'created_at' => new \DateTime('2017-03-22 10:00:05')
			],
			'php_drupal' => [
				'title' => 'Help with drupal 7',
				'category' => 'js',
				'created_at' => new \DateTime('2017-04-24 10:00:05')
			],
			'js_drupal2' => [
				'title' => 'Drupal module bug',
				'category' => 'php',
				'created_at' => new \DateTime('2017-08-21 10:00:05')
			],
			'php_docker' => [
				'title' => 'configured docker container for aws?',
				'category' => 'php',
				'created_at' => new \DateTime('2017-05-15 10:00:05')
			],
			'php_mvc' => [
				'title' => 'Which php framework recommend learning',
				'category' => 'php',
				'created_at' => new \DateTime('2016-05-05 10:00:05')
			],
			'php_symfony' => [
				'title' => 'Manual id in doctrine and symfony',
				'category' => 'php',
				'created_at' => new \DateTime('2016-07-07 10:00:05')
			],
			'php_symfony2' => [
				'title' => 'Error doctrine associations mappings in symfony profiler bar',
				'category' => 'php',
				'created_at' => new \DateTime('2016-07-10 10:00:05')
			],
			'php_symfony3' => [
				'title' => 'Setting up codeception and ci',
				'category' => 'php',
				'created_at' => new \DateTime('2017-02-09 10:00:05')
			],
			'php_laravel2' => [
				'title' => 'Replace eloquent by doctrine in laravel',
				'category' => 'php',
				'created_at' => new \DateTime('2016-11-17 10:00:05')
			],
			'php_symfony4' => [
				'title' => 'twig form template help',
				'category' => 'js',
				'created_at' => new \DateTime('2016-06-21 10:00:05')
			],
			'php_custom_mvc' => [
				'title' => 'I need a bespoke MVC framework',
				'category' => 'php',
				'created_at' => new \DateTime('2016-07-17 10:00:05')
			],
			'php_smtp' => [
				'title' => 'smtp server to test emails',
				'category' => 'php',
				'created_at' => new \DateTime('2016-07-17 20:00:05')
			],
			'php_nginx' => [
				'title' => 'basic nginx setup for php site',
				'category' => 'php',
				'created_at' => new \DateTime('2017-04-01 10:00:05')
			],
			'php_joomla' => [
				'title' => 'Any tutorial in joomla?',
				'category' => 'php',
				'created_at' => new \DateTime('2016-08-29 10:00:05')
			],
			'hosting_aws' => [
				'title' => 'How I setup aws?',
				'category' => 'php',
				'created_at' => new \DateTime('2017-01-18 10:00:05')
			],
			'hosting_wp' => [
				'title' => 'Cheap shared hosting for wordpress',
				'category' => 'hosting',
				'created_at' => new \DateTime('2016-12-20 10:00:05')
			],
			'hosting_vps' => [
				'title' => 'good VPS based in europe',
				'category' => 'hosting'
			],
			'php_design_patterns' => [
				'title' => 'Which PHP design patterns are more important?',
				'category' => 'php',
				'created_at' => new \DateTime('2017-06-20 10:00:05')
			]
		];
	}

	public function getOrder()
	{
		return 3;
	}
}