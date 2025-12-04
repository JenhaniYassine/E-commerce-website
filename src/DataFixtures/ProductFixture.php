<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create categories first
        $categories = [
            'Electronics' => 'Electronic devices and gadgets',
            'Sports' => 'Sports equipment and accessories',
            'Home' => 'Home appliances and furniture',
            'Fashion' => 'Clothing and fashion items'
        ];

        $categoryEntities = [];
        foreach ($categories as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $manager->persist($category);
            $categoryEntities[$name] = $category;
        }

        $products = [
            [
                'name' => 'Wireless Headphones',
                'price' => 99.99,
                'image' => 'headphones.jpg',
                'category' => 'Electronics',
                'stock' => 15,
                'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.'
            ],
            [
                'name' => 'Smart Watch',
                'price' => 199.99,
                'image' => 'smartwatch.jpg',
                'category' => 'Electronics',
                'stock' => 8,
                'description' => 'Feature-rich smartwatch with health tracking, GPS, and heart rate monitor.'
            ],
            [
                'name' => 'Running Shoes',
                'price' => 79.99,
                'image' => 'shoes.jpg',
                'category' => 'Sports',
                'stock' => 20,
                'description' => 'Lightweight running shoes with superior comfort and cushioning technology.'
            ],
            [
                'name' => 'Coffee Maker',
                'price' => 149.99,
                'image' => 'coffeemaker.jpg',
                'category' => 'Home',
                'stock' => 12,
                'description' => 'Programmable coffee maker with thermal carafe and brew strength control.'
            ],
            [
                'name' => 'Gaming Mouse',
                'price' => 49.99,
                'image' => 'mouse.jpg',
                'category' => 'Electronics',
                'stock' => 25,
                'description' => 'High-precision gaming mouse with 16,000 DPI sensor and customizable buttons.'
            ],
            [
                'name' => 'Yoga Mat',
                'price' => 29.99,
                'image' => 'yogamat.jpg',
                'category' => 'Sports',
                'stock' => 30,
                'description' => 'Non-slip yoga mat made from eco-friendly materials with carrying strap.'
            ],
            [
                'name' => 'Bluetooth Speaker',
                'price' => 69.99,
                'image' => 'speaker.jpg',
                'category' => 'Electronics',
                'stock' => 18,
                'description' => 'Portable Bluetooth speaker with 360-degree sound and 12-hour battery.'
            ],
            [
                'name' => 'Water Bottle',
                'price' => 19.99,
                'image' => 'waterbottle.jpg',
                'category' => 'Sports',
                'stock' => 50,
                'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours.'
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setImage($productData['image']);
            $product->setCategory($categoryEntities[$productData['category']]);
            $product->setStockQuantity($productData['stock']);
            $product->setDescription($productData['description']);
            $product->setStatus(true);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
