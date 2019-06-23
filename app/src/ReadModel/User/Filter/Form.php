<?php

declare(strict_types=1);

namespace App\ReadModel\User\Filter;

use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Name',
                'onchange' => 'this.form.submit()'//js сработает при изменении поля
            ]])
            ->add('email', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Email',
                'onchange' => 'this.form.submit()'
            ]])
            ->add('status', Type\ChoiceType::class, ['choices' => [
                'Wait' => User::STATUS_WAIT,
                'Active' => User::STATUS_ACTIVE,
                'Blocked' => User::STATUS_BLOCKED
            ],'required' => false, 'attr' => [
                'placeholder' => 'All Status',
                'onchange' => 'this.form.submit()'
            ]])
            ->add('role', Type\ChoiceType::class, ['choices' => [
                'Admin' => Role::ADMIN,
                'User' => Role::USER
            ],'required' => false, 'attr' => [
                'placeholder' => 'All Roles',
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