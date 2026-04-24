<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Seeds the database with sample categories and products',
)]
class SeedDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $categoriesData = [
            'Electronics' => [
                ['Wireless Headphones', 'Experience premium sound quality with our wireless headphones. Featuring advanced noise cancellation technology, comfortable over-ear design, and up to 30 hours of battery life. Perfect for music lovers, travelers, and professionals. The foldable design makes them easy to carry, while the premium materials ensure durability and long-lasting comfort.', 79.99, 'airbod.png'],
                ['Bluetooth Speaker', 'Portable speaker with amazing bass.', 59.99, 'mouse.png'],
                ['Wireless Mouse', 'Ergonomic wireless mouse for productivity.', 29.99, 'mouse.png'],
            ],
            'Fashion' => [
                ['Classic Leather Jacket', 'Timeless style and durability.', 149.99, 'item.png'],
            ],
            'Home & Garden' => [
                ['Smart Plant Sensor', 'Monitor your plants health from your phone.', 34.99, 'item.png'],
            ],
            'Sports' => [
                ['Yoga Mat Premium', 'High-quality yoga mat for your daily practice.', 29.99, 'item.png'],
            ],
            'Books' => [
                ['Web Development Guide', 'Master the art of web development with this comprehensive guide.', 24.99, 'item.png'],
            ],
        ];

        foreach ($categoriesData as $catName => $productsData) {
            $category = new Category();
            $category->setName($catName);
            $this->entityManager->persist($category);

            foreach ($productsData as $pData) {
                $product = new Product();
                $product->setName($pData[0]);
                $product->setDescription($pData[1]);
                $product->setPrice($pData[2]);
                $product->setImage($pData[3]);
                $product->setCategory($category);
                $this->entityManager->persist($product);
            }
        }

        $this->entityManager->flush();

        $io->success('Sample data has been seeded successfully!');

        return Command::SUCCESS;
    }
}
