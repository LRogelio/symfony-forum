<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TopicCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ForumController extends Controller
{
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
	public function listCategoryTopic(TopicCategory $category)
	{
		$em = $this->getDoctrine()->getManager();
		//->findBy(['categoryId' => $category->getId()]);

		$topics = $em->getRepository('AppBundle:Topic')->getTopicsWithLastComment($category->getId());

		$breadcrumbs = [
			['url' => $this->generateUrl('homepage'), 'text' => 'Forum']
		];

		return $this->render('forum/category_list_topics.html.twig', [
			'topics' => $topics,
			'title' => $category->getName(),
			'breadcrumbs' => $breadcrumbs
		]);
	}
}
