<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\TeachingSubject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AverageMarkCalculatorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marks', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prašome įvesti pažymius',
                    ]),
                ]
            ])
            ->add('teachingSubject', EntityType::class, [
                'class' => TeachingSubject::class,
                'choice_label' => function (?TeachingSubject $teachingSubject) {
                    return $teachingSubject ? $teachingSubject->getTeachingSubject() : '';
                }
            ]);
    }
}
