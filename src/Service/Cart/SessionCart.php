<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionCart implements CartInterface
{
    private const CART_SESSION_KEY = 'cart';

    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function add(int $productId, int $quantity): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get(self::CART_SESSION_KEY, []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $session->set(self::CART_SESSION_KEY, $cart);
    }

    public function getItems(): array
    {
        return $this->requestStack->getSession()->get(self::CART_SESSION_KEY, []);
    }

    public function remove(int $productId): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get(self::CART_SESSION_KEY, []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $session->set(self::CART_SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        $this->requestStack->getSession()->remove(self::CART_SESSION_KEY);
    }
}
