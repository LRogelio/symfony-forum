<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Topic;
use AppBundle\Entity\TopicCategory;
use AppBundle\Entity\TopicComment;
use AppBundle\Form\TopicCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\NewTopicType;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends Controller
{
	/**
	 * @Route("/topic/{topic}", name="topic_view")
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

		return $this->render('topic/view.html.twig', [
			'title' => $topic->getTitle(),
			'topic' => $topic,
			'comments' => $comments,
			'breadcrumbs' => $breadcrumbs,
			'currentPage' => $page
		]);
	}

	/**
	 * @Route("/category/{category}/topic/add", name="topic_add")
	 */
	public function createTopicAction(Request $request, TopicCategory $category)
	{
		if (!$this->getUser()) {
			return $this->redirectToRoute('login');
		}

		$topicEntry = (new Topic())->setCategoryId($category->getId());
		$commentEntry = (new TopicComment())->setTopic($topicEntry);

		$form = $this->createForm(NewTopicType::class, $commentEntry);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$user = $this->getUser();

			$commentEntry
	             ->setCreatedAt(new \DateTime())
	             ->setUpdatedAt(new \DateTime())
				 ->setUser($user);

			$commentEntry->getTopic()
				->setCreatedAt(new \DateTime())
				->setUpdatedAt(new \DateTime())
				->setCategory($category)
				->setUser($user);

			$em->persist($topicEntry);
			$em->persist($commentEntry);
			$em->flush();

			return $this->redirectToRoute('topic_view', ['topic' => $topicEntry->getId()]);
		}

		return $this->render('topic/add.html.twig', [
			'form' => $form->createView(),
			'category' => $category
		]);
	}

	/**
	 * @Route("/topic/{topic}/comment/add", name="topic_comment_add")
	 */
	public function createCommentAction(Request $request, Topic $topic)
	{
		if (!$this->getUser()) {
			return $this->redirectToRoute('login');
		}

		$form = $this->createForm(TopicCommentType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$topicCommentEntry = $form->getData()
				->setCreatedAt(new \DateTime())
				->setUpdatedAt(new \DateTime())
				->setUser($this->getUser())
				->setTopic($topic);

			$em->persist($topicCommentEntry);
			$em->flush();

			return $this->redirectToRoute('topic_view', ['topic' => $topic->getId()]);
		}

		return $this->render('topic/add_comment.html.twig', [
			'form' => $form->createView(),
			'topic' => $topic
		]);
	}
}