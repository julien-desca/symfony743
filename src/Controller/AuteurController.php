<?php

namespace App\Controller;

use App\Form\DeleteForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Form\AuteurType;

class AuteurController extends AbstractController
{

  private $entityManager;

  private $auteurRepository;

  public function __construct(EntityManagerInterface $entityManager, AuteurRepository $auteurRepository) {
    $this->entityManager = $entityManager;
    $this->auteurRepository = $auteurRepository;
  }

  /**
   * @Route("/auteur/create", name="create_auteur")
   */
  public function create(Request $request){
    //creation de l'entité à éditer
    $auteur = new Auteur();

    $form = $this->createForm(AuteurType::class, $auteur);

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
      //notre auteur est bien renseigné avec les données du formulaire
      $this->entityManager->persist($auteur);
      $this->entityManager->flush();
      //renvoi un redirection verrs la route "auteur_details"
      return $this->redirectToRoute('auteur_details', ['id' => $auteur->getId()]);
    }
    //rendu du formulaire
    return $this->render('auteur/create.html.twig', ['form' => $form->createView()]);
  }

  /**
   * @Route("auteur/{id}/update", name="update_auteur", requirements={"id"="\d+"})
   */
  public function update(Request $request, int $id){
        $auteur = $this->auteurRepository->find($id);
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $this->entityManager->persist($auteur);
          $this->entityManager->flush();
          return $this->redirectToRoute('auteur_details', ['id'=>$auteur->getId()]);
        }
        return $this->render("auteur/update.html.twig", ['formulaire' => $form->createView()]);
  }

  /**
   *@Route("/auteur", name="list_auteur")
   */
  public function list(Request $request){
    $auteurList =  $this->auteurRepository->findAll();
    return $this->render('auteur/list.html.twig', ['auteurList' => $auteurList]);
  }

  /**
   * 'requirements' : id doit être un entier (\d+)
   * Affiche le détails d'un auteur
   * @Route("/auteur/{id}", name="auteur_details", requirements={"id"="\d+"})
   */
  public function getDetails(Request $request, int $id){
    $auteur = $this->auteurRepository->find($id);
    $deleteForm = $this->createForm(DeleteForm::class);
    $deleteForm->handleRequest($request);
    if($deleteForm->isSubmitted() && $deleteForm->isValid()){
        $this->entityManager->remove($auteur);
        $this->entityManager->flush();
        return $this->redirectToRoute("list_auteur");
    }
    return $this->render('auteur/details.html.twig', ['auteur' => $auteur,
        'deleteForm'=>$deleteForm->createView()
    ]);
  }



}
