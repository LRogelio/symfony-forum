<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\SignupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
	/**
	 * @Route("/signup", name="signup")
	 */
	public function signupAction(Request $request)
	{
		if ($this->getUser()) {
			return $this->redirectToRoute('homepage');
		}
		
		$user = new User();
		$form = $this->createForm(SignupType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$encoder = $this->get('security.password_encoder');
			$password = $encoder->encodePassword($user, $user->getPassword());
			$user
				->setPassword($password)
				->setCreatedAt(new \DateTime());

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return $this->redirectToRoute('login');
		}

		return $this->render('login/signup.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction()
	{
		if ($this->getUser()) {
			return $this->redirectToRoute('homepage');
		}

		$helper = $this->get('security.authentication_utils');

		return $this->render('login/login.html.twig', [
			'last_username' => $helper->getLastUsername(),
			'error'         => $helper->getLastAuthenticationError(),
		]);
	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function logoutAction()
	{
	}
}