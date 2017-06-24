<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TopicCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ForumController extends Controller
{
	protected $maxTopicsPage = 15;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
	    $em = $this->getDoctrine()->getManager();
	    $categories = $em->getRepository('AppBundle:TopicCategory')->getCategoriesWithLastComment();

	    return $this->render('forum/index.html.twig', ['categories' => $categories]);
    }

	/**
	 * @Route("/category/{category}", name="category_topics")
	 */
	public function listCategoryTopic(Request $request, TopicCategory $category)
	{
		$page = $request->query->get('page', 1);

		$offset = $this->maxTopicsPage * ($page - 1);

		$em = $this->getDoctrine()->getManager();

		$topics = $em->getRepository('AppBundle:Topic')->getTopicsWithLastComment($category->getId(), $offset, $this->maxTopicsPage);

		$breadcrumbs = [
			['url' => $this->generateUrl('homepage'), 'text' => 'Forum']
		];

		return $this->render('forum/category_list_topics.html.twig', [
			'category' => $category,
			'topics' => $topics,
			'title' => $category->getName(),
			'breadcrumbs' => $breadcrumbs,
			'maxTopicsPage' => $this->maxTopicsPage,
			'currentPage' => $page
		]);
	}
}
