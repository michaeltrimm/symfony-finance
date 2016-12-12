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

    $total_cost = 0;
    $symbols = [];
    foreach($items as $item){
      $total_cost += ($item->getPricePaid() * $item->getQuantity());
      if(!in_array($item->getSymbol(), $symbols)){
        $symbols[] = $item->getSymbol();
      }
    }

    // replace this example code with whatever you need
    return $this->render("portfolio/index.html.twig", [
      "items" => $items,
      "total_cost" => $total_cost,
      "symbols" => $symbols
    ]);
  }
  
  /**
   * @Route("/portfolio/new", name="portfolio_new")
   * @Method({"GET","HEAD"})
   */
  public function newAction(Request $request)
  {
    return $this->render("portfolio/new.html.twig");
  }
  
  /**
   * @Route("/portfolio/create", name="portfolio_create")
   * @Method({"PUT","POST"})
   */
  public function createAction(Request $request)
  {
    // Symfony Objects
    $session = $request->getSession();

    // Require Current User
    $this->requireLoggedIn($session);

    // Placeholder for any possible error message
    $err = false;

    // Load Symfony Doctrine
    $em = $this->getDoctrine()->getManager();

    // Load Doctrine Table Entires
    $users_table = $this->getDoctrine()->getRepository('AppBundle:User');
    $portfolios_table = $this->getDoctrine()->getRepository('AppBundle:Portfolio');

    $user = $users_table->findOneByEmail($session->get('email'));

    // Validate User Exists
    if(!$user){
      // Unable to find account!
      return $this->noSuchAccount($session);
    }

    // Assign POST Params To Variables
    $symbol = $request->request->get('symbol');
    $price = $request->request->get('price');
    $quantity = $request->request->get('quantity');

    // Verify Acceptable Symbol
    if($symbol == "" OR strlen($symbol) > 7){
      $err = "Invalid symbol";
    }

    // Verify Acceptable Price Paid
    if(   $price == "" 
       OR floatval($price) <= 0.0 
       OR !filter_var($price, FILTER_VALIDATE_FLOAT)
    ){
      $err = "Invalid price";
    }

    // Verify Acceptable Quantity
    if($quantity == "" || floatval($quantity) <= 0.0){
      $err = "Invalid quantity";
    }

    // Either Create Entity Or Redirect Due To Error
    if(FALSE === $err){

      // Create New Entity
      $portfolio = new Portfolio();
      $portfolio->setUserId($user->getId());
      $portfolio->setSymbol($symbol);
      $portfolio->setPricePaid($price);
      $portfolio->setQuantity($quantity);

      // Save Entity
      $em->persist($portfolio);
      $em->flush();

      // Redirect
      $session->getFlashBag()->add('success', "Created $symbol inside your portfolio!");
      return $this->redirectToRoute("portfolio_index");

    } else {

      // Store Flash Error Message
      $session->getFlashBag()->add('error',$err);

      // Set Helper Session Info
      $session->set("portfolio_new_symbol", $symbol);
      $session->set("portfolio_new_price", $price);
      $session->set("portfolio_new_quantity", $quantity);
      $session->set("portfolio_new_attempted_on", time());

      // Redirect To New Form
      return $this->redirectToRoute('portfolio_new');

    }

  }

  /**
   * @Route("/portfolio/{id}/delete", requirements={"id" = "\d+"})
   * @Method({"GET","HEAD"})
   */
  public function deleteAction(Request $request, $id=null)
  {
    // Symfony Objects
    $session = $request->getSession();

    // Require Current User
    $this->requireLoggedIn($session);

    // Placeholder for any possible error message
    $err = false;

    // Load Symfony Doctrine
    $em = $this->getDoctrine()->getManager();

    // Define Doctrine Table Entries
    $users_table = $this->getDoctrine()->getRepository('AppBundle:User');
    $portfolios_table = $this->getDoctrine()->getRepository('AppBundle:Portfolio');

    // Fetch The User Entity
    $user = $users_table->findOneByEmail($session->get('email'));

    // Verify User Entity Exists
    if(!$user){
      // Unable to find account!
      return $this->noSuchAccount($session);
    }

    // Validate Integrity Of id Parameter
    if(is_null($id) || intval($id) < 1){
      $session->getFlashBag()->add('error',"Invalid ID provided!");
      return $this->redirectToRoute('portfolio_index');
    }

    // Fetch The Portfolio Entity
    $item = $portfolios_table->find($id);

    // Verify Portfolio Entity
    if(!$item){

      // No Entity Found
      $session->getFlashBag()->add('error',"Invalid ID provided!");
      return $this->redirectToRoute('portfolio_index');

    } else {

      // Entity Found, Verify Access
      if($item->getUserId() != $user->getId()){

        // Access Denied, Redirect With Error To Portfolio Index
        $session->getFlashBag()->add('error',"Permission Denied!");
        return $this->redirectToRoute('portfolio_index');

      }

    }

    // Extract Symbol
    $symbol = $item->getSymbol();

    // Remove Record
    $em->remove($item);
    $em->flush();

    // Redirect
    $session->getFlashBag()->add('info',"Removed $symbol from your portfolio!");
    return $this->redirectToRoute('portfolio_index');

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