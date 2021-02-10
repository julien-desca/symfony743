<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController {


  /**
   * @Route("/test", name="test")
   */
  public function test(Request $request){
    return new Response("<h1>Hello world !</h1>");
  }

  /**
   *  
   */
  public function testPersist(Request $request){

  }
}
