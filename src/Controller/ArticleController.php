<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/article/create", name="article_create")
     */
    public function create(Request $request){
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return new Response("Article enregistré");
        }
        return $this->render('article/create.html.twig', ['formulaire'=>$form->createView()]);
    }

    /**
     * @Route("/article", name="article_list")
     */
    public function articleList(Request $request){
        $articleList = $this->articleRepository->findAll();
        return $this->render("article/list.html.twig", ['articleList'=>$articleList]);
    }

    /**
     * @Route("stats")
     */
    public function getStat(){
        $list = [1,2];
        for($i = 0 ; $i < 100 ; $i++){
            $list[] = $list[$i] + $list[$i+1];
        }
        return new JsonResponse($list);
    }
}
