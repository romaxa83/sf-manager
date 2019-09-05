<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use cebe\markdown\MarkdownExtra;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/*
 * создаем твиговское расширение для markdown
 */
class MarkdownExtension extends AbstractExtension
{
    private $markdown;

    public function __construct(MarkdownExtra $markdown)
    {
        $this->markdown = $markdown;
    }
    // отключаем encode html , т.к. он сам их декодирует
    public function getFilters(): array
    {
        return [
            new TwigFilter('markdown', [$this, 'markdown'], ['is_safe' => ['html']]),
        ];
    }

    public function markdown(?string $text): string
    {
        return $this->markdown->parse($text);
    }
}