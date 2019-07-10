<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Create;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

//свой тип для формы
class NamesType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this);
    }

    /**
     * указываем от какого типа формы наш тип наследуеться
     * @return string
     */
    public function getParent(): string
    {
        return Type\TextareaType::class;
    }

    /**
     * трансформирует данные во внутреннее представление,
     * которое потом отправиться в форму
     * @param NameRow[] $value
     * @return string
     */
    public function transform($value): string
    {
        $lines = [];
        return implode(PHP_EOL, array_map(static function (NameRow $row) {
            return $row->name;
        }, $lines));
    }

    /**
     * трансформирует данные из формы в нашу команду
     * @param mixed $value
     * @return array|mixed
     */
    public function reverseTransform($value)
    {
        return array_filter(array_map(static function ($name) {
            if (empty($name)) {
                return null;
            }
            return new NameRow($name);
        }, preg_split('#[\r\n]+#', $value)));
    }
}