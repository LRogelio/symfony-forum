<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TopicCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ForumController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
	    $em = $this->getDoctrine()->getManager();
	    $categories = $em->getRepository('AppBundle:TopicCategory')->findAll();

	    $latestComments = [];
	    foreach ($categories as $category) {
	    	$categoryId = $category->getId();
	    	$latestComments[$categoryId] = $em->getRepository('AppBundle:TopicComment')->getLatestCommentByCategory($categoryId);
	    }

	    return $this->render('forum/index.html.twig', [
	    	'categories' => $categories,
		    'latestComments' => $latestComments
	    ]);
    }

	/**
	 * @Route("/category/{category}", name="category_topics")
	 */
	public function listCategoryTopicsAction(Request $request, TopicCategory $category)
	{
		$maxTopicsPage = $this->container->getParameter('maxTopicsPage');

		$page = $request->query->get('page', 1);

		$offset = $maxTopicsPage * ($page - 1);

		$em = $this->getDoctrine()->getManager();

		$topics = $em->getRepository('AppBundle:Topic')->getTopics($category->getId(), $offset, $maxTopicsPage);

		$breadcrumbs = [
			['url' => $this->generateUrl('homepage'), 'text' => 'Forum']
		];

		return $this->render('forum/list_category_topics.html.twig', [
			'category' => $category,
			'topics' => $topics,
			'title' => $category->getName(),
			'breadcrumbs' => $breadcrumbs,
			'maxTopicsPage' => $maxTopicsPage,
			'currentPage' => $page
		]);
	}
}
