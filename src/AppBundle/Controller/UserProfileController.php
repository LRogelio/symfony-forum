<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends Controller
{
	/**
	 * @Route("/profile", name="profile_dashboard")
	 */
	public function dashboardAction()
	{
		// also deny with this or annotation @Security("has_role('ROLE_ADMIN')")
		//$this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');



		return new Response('<h1>Profile dashboard</h1>');
	}

	/**
	 * @Route("/profile/edit", name="profile_edit")
	 */
	public function editAction()
	{
		return new Response('<h1>Profile edit account</h1>');
	}
}