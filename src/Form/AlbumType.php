<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Style;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>"Nom de l'album"
            ])
            ->add('date',IntegerType::class,[
                'label'=>"Année de l'album"
            ])
            ->add('image',TextType::class,[
                'label'=>"Image"
            ])
            ->add('artiste', EntityType::class,[
                'class'=>Artiste::class,
                'choice_label'=>'nom',
                'label'=>"Nom de l'artiste"
            ])

            
            ->add('styles',EntityType::class,[
                'class'=>Style::class,
                'choice_label' => 'nom',
                'label' => 'Style(s)',
                'multiple'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
