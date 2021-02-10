<?php


namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategoryType;
use App\Form\DeleteForm;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(EntityManagerInterface $entityManager, CategorieRepository $categorieRepository)
    {
        $this->entityManager = $entityManager;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/categorie/{id}", name="details_categorie", requirements={"id"="\d+"})
     */
    public function getDetails(Request $request, int $id){
        $categorie = $this->getCategorie($id);
        $form = $this->createForm(DeleteForm::class);
        $form->handleRequest($request);

        //PAS OBLIGATOIRE ! protection contre la faille CSRF pour le delete
        if($form->isSubmitted()){
            $this->entityManager->remove($categorie);
            $this->entityManager->flush();
            return $this->redirectToRoute("list_categorie");
        }

        return $this->render("categorie/details.html.twig", ['categorie'=>$categorie, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/categorie/{id}/delete", name="delete_cat")
     */
    public function delete(Request $request, int $id){
        $categorie = $this->categorieRepository->find($id);
        $this->entityManager->remove($categorie);
        $this->entityManager->flush();
        return $this->redirectToRoute("list_categorie");
    }

    /**
     * @Route("/categorie/", name="list_categorie")
     */
    public function getList(Request $request){
        $categories = $this->categorieRepository->findAll();
        return $this->render("categorie/list.html.twig", ['categorieList'=>$categories]);
    }

    /**
     * @Route("/categorie/create", name="create_categorie")
     */
    public function create(Request $request){
        $categorie = new Categorie();
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
            return $this->redirectToRoute('details_categorie', ['id'=>$categorie->getId()]);
        }
        return $this->render('categorie/create.html.twig', ['formulaire' => $form->createView()]);
    }

    /**
     * @Route("categorie/{id}/update", name="update_categorie", requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id){
        $categorie = $this->getCategorie($id);
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
            return $this->redirectToRoute('details_categorie', ['id'=>$categorie->getId()]);
        }
        return $this->render('categorie/update.html.twig', ['formulaire' => $form->createView()]);
    }

    private function getCategorie(int $id){
        $categorie = $this->categorieRepository->find($id);
        if($categorie === null){
            throw new NotFoundHttpException("Categorie introuvable");
        }
        return $categorie;
    }
}