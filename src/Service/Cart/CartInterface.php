<?php

namespace App\Service\Cart;

interface CartInterface
{
    public function add(int $productId, int $quantity): void;
    public function getItems(): array;
    public function remove(int $productId): void;
    public function clear(): void;
}
