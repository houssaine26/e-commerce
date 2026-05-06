<?php

namespace App\Service\Cart;

class ApiCart implements CartInterface
{
    public function add(int $productId, int $quantity): void
    {
        dd('Adding product ' . $productId . ' with quantity ' . $quantity . ' via API');
    }

    public function getItems(): array
    {
        dd('Getting items via API');
        return [];
    }

    public function remove(int $productId): void
    {
        dd('Removing product ' . $productId . ' via API');
    }

    public function clear(): void
    {
        dd('Clearing cart via API');
    }
}
