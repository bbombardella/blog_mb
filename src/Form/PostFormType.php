<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('description', TextType::class)
            ->add('categories', EntityType::class, [
                'label' => 'Catégorie(s)',
                'by_reference' => false,
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'required' => true
            ])
            ->add('content', TextType::class, ['label' => 'Contenu'])
            ->add('publishedAt', DateTimeType::class, [
                'label' => 'Date de publication',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
