<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\User;


class UserController extends Controller
{
  /**
   * @Route("/user", name="user_index")
   * @Method({"GET","HEAD"})
   */
  public function indexAction(Request $request)
  {
    // replace this example code with whatever you need
    return $this->redirectToRoute("user_sign_in");
  }
  
  /**
   * @Route("/user/create")
   * @Method({"POST","PUT"})
   */
  public function createAction(Request $request)
  {
    // Symfony Objects
    $em = $this->getDoctrine()->getManager();
    $session = $request->getSession();
    
    // Placeholder for any possible error message
    $err = false;
    
    // Input params
    $email = $request->request->get('email');
    $password = $request->request->get('password');
    $confirm = $request->request->get('confirm');
    
    // Validate & Sanitize Email Address
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    } else {
      $err = "Invalid email address!";
    }
    
    // Validate Password Length
    if(strlen($password) < 6){
      $err = "Insufficient password length. Must be at least 6 characters!";
    }
    
    // Validate Passwords Match
    if($password !== $confirm){
      $err = "Passwords do not match! Please fix before signing up!";
    }
    
    // Respond To Errors
    if(false !== $err){
      $request->getSession()->getFlashBag()->add('error',$err);
      $session->set("sign_up_email",$email);
      return $this->redirectToRoute('user_sign_up');
    }
    
    // Encrypt Password
    $encrypted_password = hash_hmac("sha256", $password, $this->container->getParameter('hmac_key'));
    
    // Create User Entity
    $user = new User();
    $user->setEmail($email);
    $user->setPassword($encrypted_password);
    $user->setCreatedOn(new \DateTime());
    
    // Save The User
    $em->persist($user);
    
    // Create Authentication Session
    $session->set("logged_in", true);
    $session->set("email", $email);
    
    // Provide Feedback
    $request->getSession()->getFlashBag()->add('success','Successfully signed up!');
    
    // Make Symfony Happy
    $em->flush();
    
    // Redirect User
    return $this->redirectToRoute('homepage');
  }
  
  /**
   * @Route("/user/sign_up", name="user_sign_up")
   * @Method({"GET","HEAD"})
   */
  public function signupAction(Request $request)
  {
    $session = $request->getSession();
    if(true === $session->get('logged_in')){
      $request->getSession()->getFlashBag()->add('warning',"Already signed in!");
      return $this->redirectToRoute('homepage');
    }
    return $this->render('user/sign_up.html.twig');
  }
  
  /**
   * @Route("/user/sign_in", name="user_sign_in")
   * @Method({"GET"})
   */
  public function signinAction(Request $request)
  {
    $session = $request->getSession();
    if(true === $session->get('logged_in')){
      $request->getSession()->getFlashBag()->add('warning',"Already signed in!");
      return $this->redirectToRoute('homepage');
    }
    
    return $this->render('user/sign_in.html.twig');
  }
  
  /**
   * @Route("/user/createSession", name="user_create")
   * @Method({"POST","PUT"})
   */
  public function createSessionAction(Request $request)
  {
    // Symfony Objects
    $em = $this->getDoctrine()->getManager();
    $session = $request->getSession();
    
    // Placeholder for any possible error message
    $err = false;
    
    // Input params
    $email = $request->request->get('email');
    $password = $request->request->get('password');
    
    // Validate & Santitize Email Address
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    } else {
      $err = "Invalid email address provided.";
    }
    
    // Validate Password
    if($password == "" || strlen($password) < 6){
      $err = "Invalid password provided.";
    }
    
    if(FALSE === $err){
    
      // Encrypt Password
      $encrypted_password = hash_hmac("sha256", $password, $this->container->getParameter('hmac_key'));
    
      // Get User Entity
      $users_table = $this->getDoctrine()->getRepository('AppBundle:User');
      $user = $users_table->findOneByEmail($email);
    
      // Handle No Find Error
      if(!$user){
        // No such user, give them a generic response so they can't read between the lines from the response.
        $err = "No such email address or password.";
      } else {
        if(hash_equals($encrypted_password, $user->getPassword())){
          // Successfully Logged In
          $session->set("logged_in", true);
          $session->set("email", $email);
        } else {
          // Incorrect Password, Failed To Log In
          $err = "No such email address or password.";
        }
      }
      
    }
    
    // Were There Errors?
    if(false !== $err){
      // Error Trying To Sign In
      $request->getSession()->getFlashBag()->add('error',$err);
      $session->set("sign_up_email",$email);
      return $this->redirectToRoute('user_sign_in');
    } else {
      // Successfully Signed In
      $request->getSession()->getFlashBag()->add('success',"Welcome back ".$email);
      return $this->redirectToRoute('homepage');
    }
    
  }
  
  /**
   * @Route("/user/sign_out", name="user_sign_out")
   * @Method({"GET"})
   */
  public function signoutAction(Request $request)
  {
    $session = $request->getSession();
    $session->set("logged_in", null);
    $session->set("email", null);
    
    $request->getSession()->getFlashBag()->add('success','Successfully signed out!');
    
    return $this->redirectToRoute('user_sign_in');
    
  }
}
