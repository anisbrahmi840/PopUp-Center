<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('total_students')
            ->add('course_duration')
            ->add('course_start')
            ->add('palace')
            ->add('days')
            ->add('imageFile', FileType::class, [
                'required' => false,
                'multiple' => false
            ])
            ->add('Professor', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $u) {
                    return $u->createQueryBuilder('u')
                        ->andWhere('u.roles like :roles')
                        ->setParameter('roles', '%"ROLE_PROFESSOR"%')
                        ;
                },
                'choice_label' => 'username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
