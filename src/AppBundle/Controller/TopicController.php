<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Topic;
use AppBundle\Entity\TopicComment;
use AppBundle\Form\TopicCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\TopicType;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends Controller
{
	/**
	 * @Route("/topic/{topic}", name="topic_view", requirements={"topic": "\d+"})
	 */
	public function viewTopicAction(Topic $topic)
	{
		$category = $topic->getCategory();

		$em = $this->getDoctrine()->getManager();
		$comments = $em->getRepository('AppBundle:TopicComment')->getComments($topic->getId());

		$breadcrumbs = [
			['url' => $this->generateUrl('homepage'), 'text' => 'Forum'],
			['url' => $this->generateUrl('category_topics', ['category'=>$category->getId()]), 'text' => $category->getName()]
		];

		return $this->render('topic/view.html.twig', [
			'title' => $topic->getTitle(),
			'topic' => $topic,
			'comments' => $comments,
			'breadcrumbs' => $breadcrumbs
		]);
	}

	/**
	 * @Route("/topic/add", name="topic_add")
	 */
	public function createTopicAction(Request $request)
	{
		$topicEntry = (new Topic())
			->setCreatedAt(new \DateTime())
			->setUpdatedAt(new \DateTime());

		$form = $this->createForm(TopicType::class, $topicEntry);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getManager();

			$em->persist($topicEntry);
			$em->flush();

			return $this->redirectToRoute('topic_view', ['topic' => $topicEntry->getId()]);
		}

		return $this->render('topic/add.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/topic/message/add", name="topic_comment_add")
	 */
	public function createCommentAction(Request $request)
	{
		$topicCommentEntry = (new TopicComment())
			->setCreatedAt(new \DateTime())
			->setUpdatedAt(new \DateTime());

		$form = $this->createForm(TopicCommentType::class, $topicCommentEntry);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getManager();

			$em->persist($topicCommentEntry);
			$em->flush();

			return $this->redirectToRoute('homepage');
		}

		return $this->render('topic/add_comment.html.twig', [
			'form' => $form->createView()
		]);
	}

	public function editCommentAction()
	{

	}

	// ajax action that previews the rendered message
	public function previewCommentAction()
	{

	}
}