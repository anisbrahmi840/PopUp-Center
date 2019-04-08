<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('password')
            ->add('roles', ChoiceType::class ,  [
                'choices' => ['ROLE_ADMIN', 'ROLE_STUDENT', 'ROLE_PROFESSOR'],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
