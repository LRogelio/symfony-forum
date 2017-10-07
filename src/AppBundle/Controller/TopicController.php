<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Topic;
use AppBundle\Entity\TopicComment;
use AppBundle\Events;
use AppBundle\Form\TopicCommentType;
use AppBundle\Form\TopicType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends Controller
{
	/**
	 * @Route("/topic/{topic}", name="topic_view", requirements={"topic": "\d+"})
	 */
	public function viewTopicAction(Request $request, Topic $topic)
	{
		$maxCommentsPage = $this->container->getParameter('maxCommentsPage');

		$page = $request->query->get('page', 1);

		$offset = $maxCommentsPage * ($page - 1);

		$category = $topic->getCategory();

		$em = $this->getDoctrine()->getManager();
		$comments = $em->getRepository('AppBundle:TopicComment')->getComments($topic->getId(), $offset, $maxCommentsPage);

		$breadcrumbs = [
			['url' => $this->generateUrl('homepage'), 'text' => 'Forum'],
			['url' => $this->generateUrl('category_topics', ['category'=>$category->getId()]), 'text' => $category->getName()]
		];

		return $this->render('topic/view_topic.html.twig', [
			'title' => $topic->getTitle(),
			'topic' => $topic,
			'comments' => $comments,
			'breadcrumbs' => $breadcrumbs,
			'currentPage' => $page
		]);
	}

	/**
	 * @Route("/topic/add", name="topic_add")
	 */
	public function addTopicAction(Request $request)
	{
		if (!$this->getUser()) {
			return $this->redirectToRoute('login');
		}

		$topicEntry = (new Topic())
			->addComment(new TopicComment());

		$categoryId = $request->query->get('category');
		if ($categoryId) {
			$category = $this->getDoctrine()->getRepository('AppBundle:TopicCategory')->find($categoryId);
			$topicEntry->setCategory($category);
		}

		$form = $this->createForm(TopicType::class, $topicEntry, ['comments' => true]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$user = $this->getUser();
			$topicEntry = $form->getData();

			$topicEntry
				->setUser($user)
				->setCreatedAt(new \DateTime())
				->setUpdatedAt(new \DateTime());

			$topicEntry->getComments()->first()
				->setCreatedAt(new \DateTime())
				->setUpdatedAt(new \DateTime())
				->setTopic($topicEntry)
				->setUser($user);

			$em->persist($topicEntry);
			$em->flush();

			return $this->redirectToRoute('topic_view', ['topic' => $topicEntry->getId()]);
		}

		return $this->render('topic/add_topic.html.twig', [
			'form' => $form->createView(),
			'categoryId' => $categoryId
		]);
	}

	/**
	 * @Route("/topic/edit/{id}", name="topic_edit")
	 */
	public function editTopicAction(Request $request, Topic $topicEntry)
	{
		if (!$this->getUser() || $this->getUser()->getId() !== $topicEntry->getUser()->getId()) {
			return $this->redirectToRoute('login');
		}

		$form = $this->createForm(TopicType::class, $topicEntry);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$form->getData()->setUpdatedAt(new \DateTime());
			$em->flush();

			return $this->redirectToRoute('topic_view', ['topic' => $topicEntry->getId()]);
		}

		return $this->render('topic/edit_topic.html.twig', [
			'form' => $form->createView(),
			'topic' => $topicEntry
		]);
	}

	/**
	 * @Route("/topic/{topic}/comment/add", name="topic_comment_add")
	 */
	public function addCommentAction(Request $request, Topic $topic)
	{
		if (!$this->getUser()) {
			return $this->redirectToRoute('login');
		}

		$form = $this->createForm(TopicCommentType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$commentEntry = $form->getData()
				->setCreatedAt(new \DateTime())
				->setUpdatedAt(new \DateTime())
				->setUser($this->getUser())
				->setTopic($topic);

			$em->persist($commentEntry);
			$em->flush();

			$eventDispatcher = $this->get('event_dispatcher');
			$eventDispatcher->dispatch(Events::TOPIC_COMMENT_CREATED, new GenericEvent($commentEntry));

			return $this->redirectToRoute('topic_view', ['topic' => $topic->getId()]);
		}

		return $this->render('topic/add_comment.html.twig', [
			'form' => $form->createView(),
			'topic' => $topic
		]);
	}

	/**
	 * @Route("/topic/comment/edit/{id}", name="topic_comment_edit")
	 */
	public function editCommentAction(Request $request, TopicComment $comment)
	{
		if (!$this->getUser() || $this->getUser()->getId() !== $comment->getUser()->getId()) {
			return $this->redirectToRoute('homepage');
		}

		$form = $this->createForm(TopicCommentType::class, $comment);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			return $this->redirectToRoute('topic_view', [
				'topic' => $comment->getTopic()->getId()
			]);
		}

		return $this->render('topic/edit_comment.html.twig', [
			'form' => $form->createView(),
			'comment' => $comment
		]);
	}
}