<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du produit'
            ])
            ->add('description')
            ->add('price', null, [
                'label' => 'Prix'
            ])
            ->add('isPublished', null, [
                'label' => 'Le produit doit il être publié ?'
            ])
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'Image (jpg / png / jpeg)',
                ]
            )
            ->add('categories')
            ->add('tags', collectionType::class, [
                'entry_type'=> TagType::class,
                'entry_options' => ['label' => false],
                'allow_add'=>true,
                'allow_delete'=>true
            ])
            ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class
        ]);
    }
}
