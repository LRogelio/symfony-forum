<?php

namespace BulmaCSSBundle\Controller;

use BulmaCSSBundle\Form\DemoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
{
    /**
     * @Route("/bulmacss")
     */
    public function indexAction(Request $request)
    {
	    $form = $this->createForm(DemoType::class);

	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid())
	    {
		    dump('form is valid');
		    die;
	    }

        return $this->render('BulmaCSSBundle:Demo:index.html.twig', [
        	'form' => $form->createView()
        ]);
    }
}
