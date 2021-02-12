<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $repository)
    {
        $this->articleRepository = $repository;
    }

    /**
   *@Route("/", name="home")
   */
  public function home(Request $request){
    $articleList = $this->articleRepository->listDesArticlePublies();
    return $this->render('home.html.twig', ['articleList'=>$articleList]);
  }
}
