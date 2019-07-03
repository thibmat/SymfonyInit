<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$originalRoles = $this->getParameter('security.role_hierarchy.roles');
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'MODERATEUR' => 'ROLE_MODERATEUR',
                    'ADMIN' => 'ROLE_ADMIN',
                    'SUPER ADMIN' => 'ROLE_SUPER_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true
            ]);
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
