<?php
namespace App\Twig;

use App\Storage\CartSessionStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private CartSessionStorage $cartSessionStorage
    )
    {}

    public function getFunctions()
    {
        return [
            new TwigFunction('cartOrderCount', [$this, 'cartOrderCount']),
        ];
    }

    public function cartOrderCount(): ?int
    {
        return $this->cartSessionStorage->getCart()?->getItems()->count();
    }
}
