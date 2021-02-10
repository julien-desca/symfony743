<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuteurType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder->add('nom', TextType::class, ['label' => 'Veuillez entrer un nom']); //ajout d'un champs de formulaire
    $builder->add('prenom', TextType::class, [
      'label' => 'Veuillez entrer un prenom',
      'required' => false
    ]);
    $builder->add('sauvegarder', SubmitType::class, ['label' => 'sauvegarder']);
  }

}
