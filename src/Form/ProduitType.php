<?php

namespace App\Form;

use App\Entity\Produit;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('imageName', FileType::class, [
                'label' => 'Image (jpg / png / jpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Le type de fichier n\'est pas valide',
                    ])
                ],
            ])
            ->add('categories')
            ->add('tags')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class
        ]);
    }
}
