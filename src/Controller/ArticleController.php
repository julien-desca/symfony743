<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Service\FakeArticleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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

    private $fakeArticleService;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository, FakeArticleService $fakeArticleService)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
        $this->fakeArticleService = $fakeArticleService;
    }


    /**
     * @Route("/article/{id}", name="article_details", requirements={"id"="\d+"})
     */
    public function getDetail(Request $request, int $id){
        $article = $this->articleRepository->find($id);

        $commentaire = new Commentaire();
        $commentaire->setArticle($article);

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();
            return $this->redirectToRoute('article_details', ['id'=>$id]);
        }

        return $this->render('article/details.html.twig', ['article'=>$article, 'formulaire'=>$form->createView()]);
    }

    /**
     * @Route("/article/create", name="article_create")
     * @IsGranted("ROLE_AUTEUR")
     */
    public function create(Request $request){
        $user = $this->getUser();
        $article = new Article();
        $article->setUser($user);
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
     * @Route("/article/fake", name="article_fake")
     */
    public function createFakeArticle(Request $request){
        $article = $this->fakeArticleService->getFakeArticle(); /*Appel de notre service */;
        $user = $this->getUser();
        $article->setUser($user);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('article_details', ['id'=>$article->getId()]);
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

}
