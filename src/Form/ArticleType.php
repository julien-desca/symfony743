<?php


namespace App\Form;


use App\Entity\Auteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class, ['label'=>'Titre de l\'article']);
        $builder->add('sousTitre', TextType::class, ['label'=>'Sous-titre de l\'article']);
        $builder->add('contenu', TextareaType::class, ['label' => 'Contenu de l\'article']);
        $builder->add('publie', CheckboxType::class, ['label'=>'Publier l\'article ?']);
        $builder->add('dateDePublication', DateTimeType::class, ['label'=>'Date de publication']);
        $builder->add('auteur', EntityType::class, [
            'label'=>'auteur de l\'article',
            'class' => Auteur::class,
            'choice_label' => function($auteur){
                return $auteur->getNom() . " " . $auteur->getPrenom();
            }
        ]);
    }

}