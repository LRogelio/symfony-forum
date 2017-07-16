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
				->setEmail($item['email']);

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
				'email' => 'foo@example.org'
			],
			'king_php' => [
				'username' => 'king_php',
				'email' => 'baz@example.org'
			],
			'js-sensei' => [
				'username' => 'js-sensei',
				'email' => 'bar@example.org'
			],
			'dba2000' => [
				'username' => 'dba2000',
				'email' => 'dba@example.org'
			],
			'test' => [
				'username' => 'test',
				'email' => 'test@example.org'
			]
		];
	}

	public function getOrder()
	{
		return 1;
	}
}