<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\User;
use AppBundle\Entity\Portfolio;


class PortfolioController extends Controller
{
  /**
   * @Route("/portfolio", name="portfolio_index")
   * @Method({"GET","HEAD"})
   */
  public function indexAction(Request $request)
  {
    // Symfony Objects
    $session = $request->getSession();
    
    // Require Current User
    $this->requireLoggedIn($session);
    
    // Load Symfony Doctrine
    $em = $this->getDoctrine()->getManager();
    
    $users_table = $this->getDoctrine()->getRepository('AppBundle:User');
    $portfolios_table = $this->getDoctrine()->getRepository('AppBundle:Portfolio');
    
    $user = $users_table->findOneByEmail($session->get('email'));
    
    if(!$user){
      // Unable to find account!
      return $this->noSuchAccount($session);
    }
    
    $items = $portfolios_table->findByUserId($user->getId());
    
    // replace this example code with whatever you need
    return $this->render("portfolio/index.html.twig", ["items" => $items]);
  }
  
  private function requireLoggedIn($session){
    if(false === $session->get('logged_in') || empty($session->get('email'))){
      return $this->noSuchAccount($session);
    }
  }
  
  private function noSuchAccount($session){
    // No Such Active Session
    $session->getFlashBag()->add('error',"Not signed in!");
    return $this->redirectToRoute('user_sign_in');
  }
}