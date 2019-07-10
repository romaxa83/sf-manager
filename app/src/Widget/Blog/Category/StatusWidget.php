<?php
declare(strict_types=1);

namespace App\Widget\Blog\Category;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StatusWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('category_status', [$this, 'status'],
                ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function status(Environment $twig, string $status): string
    {
        return $twig->render('widget/blog/category/status.html.twig', [
            'status' => $status
        ]);
    }
}