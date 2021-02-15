<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("prenom", TextType::class, ['label'=>'Veuillez entrer votre prénom']);
        $builder->add("nom", TextType::class, ['label'=>'Veuillez entrer votre nom']);
        $builder->add("email", EmailType::class, ['label'=>'Veuillez entrer votre e-mail']);
        $builder->add("password", RepeatedType::class, [
            'type' => TextType::class,
            'first_options'  => ['label' => 'Votre mot de passe'],
            'second_options' => ['label' => 'Repeter votre mot de passe'],
        ]);
    }

}