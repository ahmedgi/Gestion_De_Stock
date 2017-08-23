<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */
    public function HomeAction()
    {
    	if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
   			 // user is logged in
    		return $this->redirectToRoute('login');
		}
        return $this->render('default/home.html.twig');
    }
   
}
?>