<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
	private $factory;

	public function __construct(FactoryInterface $factory)
	{
		$this->factory = $factory;
	}

	public function createPanelMenu(array $options)
	{
		$menu = $this->factory->createItem('root', [
			'childrenAttributes' => [
				'class' => 'menu-list'
			]
		]);

		$menu->addChild('Dashboard', ['route' => 'panel_dashboard']);

		$menu->addChild('Forum')->setAttribute('class', 'menu-label');
		$menu->addChild('Topics', ['route' => 'panel_forum_topics']);
		$menu->addChild('Comments', ['route' => 'panel_forum_comments']);

		$menu->addChild('User')->setAttribute('class', 'menu-label');
		$menu->addChild('Edit Profile', ['route' => 'panel_edit_profile']);
		$menu->addChild('Change Password', ['route' => 'panel_change_password']);

		return $menu;
	}
}