<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\{User, Mark, TeachingSubject};
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MarkInsertFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('student', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (?User $user) {
                    return ($user && $user->getName() != 'admin') ? $user->getName() . ' ' . $user->getSurname() : '-';
                }
            ])
            ->add('teachingSubject', EntityType::class, [
                'class' => TeachingSubject::class,
                'choice_label' => function (?TeachingSubject $teachingSubject) {
                    return $teachingSubject ? $teachingSubject->getTeachingSubject() : '';
                }
            ])
            ->add('mark', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                    'message' => 'Prašome įvesti skaičių nuo 1 iki 10',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prašome įvesti mokinio pažymį',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
        ]);
    }
}
