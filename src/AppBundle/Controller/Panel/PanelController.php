<?php

namespace AppBundle\Controller\Panel;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PanelController extends Controller
{
	/**
	 * @Route("/", name="panel_dashboard")
	 */
	public function dashboardAction()
	{
		$user = $this->getUser();
		$topics = $this->getDoctrine()->getRepository('AppBundle:Topic')->getTopicsByUser($user->getId());
		$totalTopics = count($topics);

		return $this->render('panel/dashboard.html.twig', [
			'user' => $user,
			'totalTopics' => $totalTopics
		]);
	}

	/**
	 * @Route("/forum/topics", name="panel_forum_topics")
	 */
	public function listTopicsAction()
	{
		$user = $this->getUser();
		$topics = $this->getDoctrine()->getRepository('AppBundle:Topic')->getTopicsByUser($user->getId());

		return $this->render('panel/list_topics.html.twig', [
			'topics' => $topics
		]);
	}

	/**
	 * @Route("/forum/comments", name="panel_forum_comments")
	 */
	public function listTopicCommentsAction()
	{
		$user = $this->getUser();
		$comments = $this->getDoctrine()->getRepository('AppBundle:TopicComment')->getCommentsByUser($user->getId());

		return $this->render('panel/list_comments.html.twig', [
			'comments' => $comments
		]);
	}

	/**
	 * @Route("/user/edit", name="panel_edit_profile")
	 */
	public function editProfileAction()
	{
		$user = $this->getUser();

//		$form = $this->createForm(null, $user);
//
//		if ($form->isValid() && $form->isSubmitted()) {
//			// save
//			// redirect to current page
//		}



		return $this->render('panel/edit_profile.html.twig', [
			'content' => 'edit profile'
		]);
	}

	/**
	 * @Route("/user/change-password", name="panel_change_password")
	 */
	public function changePasswordAction()
	{
		die('change password action');
	}
}