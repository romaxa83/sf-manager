<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Create;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', Type\TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        //указываем класс который будет оборачивает эту форму
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}