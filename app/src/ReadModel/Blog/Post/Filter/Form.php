<?php

declare(strict_types=1);

namespace App\ReadModel\Blog\Post\Filter;

use App\Model\Blog\Entity\Category\Category;
use App\Model\Blog\Entity\Post\Post;
use App\Model\Blog\Entity\Post\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Title',
                'onchange' => 'this.form.submit()'//js сработает при изменении поля
            ]])
            ->add('status', Type\ChoiceType::class, ['choices' => [
                'Active' => Status::ACTIVE,
                'Draft' => Status::DRAFT,
                'Postponed publication' => Status::POSTPONED_PUBLICATION
            ],'required' => false, 'placeholder' => 'All Status', 'attr' => [
                'onchange' => 'this.form.submit()'
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        //указываем класс который будет оборачивает эту форму
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}