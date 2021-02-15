<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    private $encoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="register_user")
     */
    public function registerUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //bien penser à encoder le mot de passe avec le UserPasswordEncoder !!!!
            $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword); //set du password encodé
            $user->setRoles(['ROLE_USER']); //définition des roles de l'utilisateur
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render("user/register.html.twig", ['formulaire' => $form->createView()]);
    }
}