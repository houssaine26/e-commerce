<?php

namespace App\Service\Cart;

use App\Dto\CartItemDto;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartHandler
{
    public function __construct(
        #[Autowire(service: SessionCart::class)]
        private CartInterface $cartStrategy
    ) {
    }

    public function handle(CartItemDto $cartItemDto): void
    {
        $this->cartStrategy->add($cartItemDto->productId, $cartItemDto->quantity);
    }

    public function getCartItems(): array
    {
        return $this->cartStrategy->getItems();
    }

    public function remove(int $productId): void
    {
        $this->cartStrategy->remove($productId);
    }
}
