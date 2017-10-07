<?php

namespace AppBundle\Controller\Panel;

use AppBundle\Events;
use AppBundle\Form\Panel\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Panel\EditProfileType;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
	/**
	 * @Route("/user/edit", name="panel_edit_profile")
	 */
	public function editProfileAction(Request $request)
	{
		$user = $this->getUser();

		$form = $this->createForm(EditProfileType::class, $user);
		$form->handleRequest($request);

		if ($form->isValid() && $form->isSubmitted()) {

			$this->getDoctrine()->getEntityManager()->flush();

			$this->addFlash('notice', 'Your profile has been updated successfully');
			$this->redirectToRoute('panel_edit_profile');
		}

		return $this->render('panel/profile/edit_profile.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/user/change-password", name="panel_change_password")
	 */
	public function changePasswordAction(Request $request)
	{
		$form = $this->createForm(ChangePasswordType::class, []);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$user = $this->getUser();
			$data = $form->getData();
			$encoder = $this->get('security.password_encoder');
			$password = $encoder->encodePassword($user, $data['newPassword']);
			$user->setPassword($password);

			$this->getDoctrine()->getEntityManager()->flush();

			$eventDispatcher = $this->get('event_dispatcher');
			$eventDispatcher->dispatch(Events::USER_CHANGE_PASSWORD, new GenericEvent($this->getUser()));

			$this->addFlash('notice', 'Your password has been updated successfully');
			return $this->redirectToRoute('panel_change_password');
		}

		return $this->render('panel/profile/change_password.html.twig', [
			'form' => $form->createView()
		]);
	}
}