<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\TopicComment;

class LoadTopicCommentsData extends AbstractBaseFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$this->truncate($manager, 'topic_comment');

		$data = $this->getData();

		foreach ($data as $key => $item) {
			$topic = $this->getReference('topic-' . $item['topic']);
			$user = $this->getReference('user-' . $item['user']);

			$date = $topic->getCreatedAt();

			for ($i=1;$i<=$item['comments'];$i++) {

				if ($i>1) {
					$date = clone $date;
					$date->modify('+20 minutes');
				}

				$comment = (new TopicComment())
					->setTopic($topic)
					->setMessage('#'.$i.' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porttitor eget urna ac ultrices. In ac luctus leo. Sed eget elit iaculis, luctus neque vitae, auctor tortor. Sed nec eros a sem eleifend suscipit quis sit amet neque. Pellentesque rhoncus massa quis diam luctus facilisis. Donec eu bibendum enim, iaculis suscipit orci. Nulla sed commodo augue, vel ultricies urna. Duis lorem elit, maximus at lectus ac, vestibulum placerat eros.')
					->setCreatedAt($date)
					->setUpdatedAt($date)
					->setUser($user);

				$manager->persist($comment);
			}
		}

		$manager->flush();
	}

	protected function getData()
	{
		return [
			[
				'topic' => 'css_ie',
				'comments' => 15,
				'user' => 'js-sensei'
			],
			[
				'topic' => 'css_html5',
				'comments' => 7,
				'user' => 'js-sensei'
			],
			[
				'topic' => 'css_framework',
				'comments' => 34,
				'user' => 'js-sensei'
			],
			[
				'topic' => 'css_sass',
				'comments' => 9,
				'user' => 'js-sensei'
			],
			[
				'topic' => 'js_jquery',
				'comments' => 21,
				'user' => 'js-sensei'
			],
			[
				'topic' => 'js_node',
				'comments' => 17,
				'user' => 'reactmaster'
			],
			[
				'topic' => 'js_oop',
				'comments' => 25,
				'user' => 'reactmaster'
			],
			[
				'topic' => 'js_es6',
				'comments' => 13,
				'user' => 'reactmaster'
			],
			[
				'topic' => 'js_node_wp',
				'comments' => 8,
				'user' => 'reactmaster'
			],
			[
				'topic' => 'js_angular',
				'comments' => 48,
				'user' => 'js-sensei'
			],
			[
				'topic' => 'php_vm',
				'comments' => 53,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_laravel',
				'comments' => 20,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_mysql',
				'comments' => 31,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_tutorial',
				'comments' => 5,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_drupal',
				'comments' => 73,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_docker',
				'comments' => 16,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_mvc',
				'comments' => 44,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_symfony',
				'comments' => 10,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_symfony2',
				'comments' => 70,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_symfony3',
				'comments' => 7,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_laravel2',
				'comments' => 12,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_symfony4',
				'comments' => 1,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_custom_mvc',
				'comments' => 19,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_smtp',
				'comments' => 14,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_nginx',
				'comments' => 29,
				'user' => 'king_php'
			],
			[
				'topic' => 'php_joomla',
				'comments' => 50,
				'user' => 'king_php'
			],
			[
				'topic' => 'hosting_aws',
				'comments' => 1,
				'user' => 'dba2000'
			],
			[
				'topic' => 'hosting_wp',
				'comments' => 15,
				'user' => 'dba2000'
			],
			[
				'topic' => 'php_design_patterns',
				'comments' => 1,
				'user' => 'test'
			],
			[
				'topic' => 'js_drupal2',
				'comments' => 1,
				'user' => 'king_php'
			]
		];
	}

	public function getOrder()
	{
		return 4;
	}
}