<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadUsersData extends AbstractBaseFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	public function load(ObjectManager $manager)
	{
		$this->truncate($manager, 'user');

		$data = $this->getData();

		$encoder = $this->container->get('security.password_encoder');

		foreach ($data as $key => $item)
		{
			$user = (new User())
				->setUsername($item['username'])
				->setEmail($item['email'])
				->setCreatedAt(new \DateTime($item['created_at']));

			$password = $encoder->encodePassword($user, '1234');
			$user->setPassword($password);

			$this->addReference('user-' . $key, $user);

			$manager->persist($user);
		}

		$manager->flush();
	}

	protected function getData()
	{
		return [
			'reactmaster' => [
				'username' => 'reactmaster',
				'email' => 'foo@example.org',
				'created_at' => '2017-01-20 19:00:05'
			],
			'king_php' => [
				'username' => 'king_php',
				'email' => 'baz@example.org',
				'created_at' => '2017-01-25 19:00:05'
			],
			'js-sensei' => [
				'username' => 'js-sensei',
				'email' => 'bar@example.org',
				'created_at' => '2017-02-15 11:00:05'
			],
			'dba2000' => [
				'username' => 'dba2000',
				'email' => 'dba@example.org',
				'created_at' => '2017-05-09 08:30:05'
			],
			'test' => [
				'username' => 'test',
				'email' => 'test@example.org',
				'created_at' => '2017-02-28 23:00:05'
			]
		];
	}

	public function getOrder()
	{
		return 1;
	}
}