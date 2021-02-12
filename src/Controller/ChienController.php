<?php


namespace App\Controller;

use App\Entity\Chien;
use App\Form\ChienType;
use App\Form\DeleteForm;
use App\Repository\ChienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ChienController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ChienRepository
     */
    private $chienRepository;

    public function __construct(EntityManagerInterface $entityManager, ChienRepository $chienRepository){
        $this->entityManager = $entityManager;
        $this->chienRepository = $chienRepository;
    }


    /**
     * @Route("/chien/{id}", name="chien_details", requirements={"id"="\d+"})
     */
    public function getDetails(Request $request, int $id){
        $chien = $this->chienRepository->find($id);
        if($chien === null){
            throw new NotFoundHttpException("chien introuvable"); //renvoie un exception si le chien demandé n'as pas été trouvé.
        }
        //utilisation d'un formulaire 'vide' pour contrer la faille CSRF
        $deleteForm = $this->createForm(DeleteForm::class);
        $deleteForm->handleRequest($request);
        if($deleteForm->isSubmitted() && $deleteForm->isValid()){
            $this->entityManager->remove($chien);
            $this->entityManager->flush();
            return $this->redirectToRoute("chien_list");
        }
        return $this->render('chien/details.html.twig', ['chien'=>$chien, 'deleteForm' => $deleteForm->createView()]);

    }

    /**
     * @Route("/chien/create", name="chien_create")
     */
    public function create(Request $request){
        $chien = new Chien(); // l'entité qu'on va éditer
        $form = $this->createForm(ChienType::class, $chien); //création du formulaire

        $form->handleRequest($request); //liaison du formulaire et de la requete HTTP
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($chien);
            $this->entityManager->flush();
            return $this->redirectToRoute('chien_details', [ 'id' => $chien->getId() ]); //redirection vers le détail
        }
        return $this->render('chien/create.html.twig', ['formulaire' => $form->createView()]);
    }

    /**
     * @Route("/chien/", name="chien_list")
     */
    public function getList(Request $request){
        $chienList = $this->chienRepository->findAll();
        return $this->render('chien/list.html.twig', ['chienList' => $chienList]);
    }

    /**
     * @Route("/chien/{id}/update", name="chien_update", requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id){
        $chien = $this->chienRepository->find($id); // l'entité qu'on va éditer
        if($chien === null){
            throw new NotFoundHttpException("chien introuvable");
        }
        $form = $this->createForm(ChienType::class, $chien); //création du formulaire
        $form->handleRequest($request); //liaison du formulaire et de la requete HTTP
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($chien);
            $this->entityManager->flush();
            return $this->redirectToRoute('chien_details', [ 'id' => $chien->getId() ]); //redirection vers le détail
        }
        return $this->render('chien/update.html.twig', ['formulaire' => $form->createView()]);
    }

    /**
     * @Route("/chien/{id}/delete", name="chien_delete", requirements={"id"="\d+"})
     */
    public function delete(Request $request , int $id){
        $chien = $this->chienRepository->find($id);
        if($chien === null){
            throw new NotFoundHttpException("chien introuvable");
        }
        $this->entityManager->remove($chien);
        $this->entityManager->flush();
        return $this->redirectToRoute("chien_list");
    }
}