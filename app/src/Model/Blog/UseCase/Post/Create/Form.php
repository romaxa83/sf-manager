<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Post\Create;

use App\ReadModel\Blog\Category\CategoryFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class Form extends AbstractType
{
    private $categoryFetcher;

    public function __construct(CategoryFetcher $categoryFetcher)
    {
        $this->categoryFetcher = $categoryFetcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        dd($this->categoryFetcher->getAllActiveForSelect());

        $builder
            ->add('title', Type\TextType::class)
            ->add('category', Select2EntityType::class, [
//                'multiple' => true,
                'remote_route' => 'blog.posts',
                'class' => 'App\Model\Blog\Entity\Category',
                'text_property' => 'name',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'placeholder' => 'Select a country',
            ])
            ->add('body', Type\TextType::class);

        dd($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        //указываем класс который будет оборачивает эту форму
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}