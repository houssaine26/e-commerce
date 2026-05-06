<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartHandler;
use App\Dto\CartItemDto;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('main/browse_categories.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/products', name: 'app_products')]
    public function products(ProductRepository $productRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/details/{id}', name: 'app_details')]
    public function details(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('main/product_details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/categories/{id}', name: 'app_products_by_category')]
    public function categoryProducts(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('main/products_by_category.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }


    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('main/profile.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('main/login.html.twig');
    }

    #[Route('/categories', name: 'app_categories')]
    public function categories(): Response
    {
        return $this->render('main/browse_categories.html.twig');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(CartHandler $cartHandler, ProductRepository $productRepository): Response
    {
        $cartItems = $cartHandler->getCartItems();
        $productsInCart = [];
        $total = 0;

        foreach ($cartItems as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $subtotal = $product->getPrice() * $quantity;
                $productsInCart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        return $this->render('main/cart.html.twig', [
            'items' => $productsInCart,
            'total' => $total
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(int $id, Request $request, CartHandler $cartHandler): Response
    {
        $quantity = $request->request->get('quantity', 1);
        $cartItemDto = new CartItemDto($id, (int)$quantity);
        $cartHandler->handle($cartItemDto);

        $this->addFlash('success', 'Product added to cart!');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(int $id, CartHandler $cartHandler): Response
    {
        $cartHandler->remove($id);
        $this->addFlash('success', 'Product removed from cart!');

        return $this->redirectToRoute('app_cart');
    }
}
