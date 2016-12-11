<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class UserController extends Controller
{
  /**
   * @Route("/user", name="users_index")
   * @Method({"GET","HEAD"})
   */
  public function indexAction(Request $request)
  {
    // replace this example code with whatever you need
    return $this->render('default/index.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
    ]);
  }
  
  /**
   * @Route("/user")
   * @Method({"POST","PUT"})
   */
  public function createAction(Request $request)
  {
    
  }
  
  /**
   * @Route("/user/sign_up", name="users_sign_up")
   * @Method({"GET","HEAD"})
   */
  public function signupAction(Request $request)
  {
    return $this->render('user/sign_up.html.twig');
  }
  
  /**
   * @Route("/user/sign_in", name="user_sign_in")
   * @Method({"GET"})
   */
  public function signinAction(Request $request)
  {
    $session = new Session();
    ob_start();
    print_r($session);
    $text = ob_get_clean();
    return $this->render('user/sign_in.html.twig', ["session" => $text]);
  }
  
  /**
   * @Route("/user/sign_out", name="user_sign_out")
   * @Method({"POST","PUT","DELETE"})
   */
  public function signoutAction(Request $request)
  {
    
  }
}
