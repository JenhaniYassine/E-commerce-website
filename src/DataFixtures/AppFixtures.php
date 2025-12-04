<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
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
            $category = new \App\Entity\Category();
            $category->setName($name);
            $category->setDescription($description);
            $manager->persist($category);
            $categoryEntities[$name] = $category;
        }

        // Create admin user
        $admin = new \App\Entity\User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$J98ZC64YjfSiwONxTd82JOQqvQobm3HRz6fD3.VfqHbMrO6M0gLU.'); // password: admin123
        $admin->setIsVerified(true);
        $manager->persist($admin);

        // Create regular user
        $user = new \App\Entity\User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$k/9jybdFO9Hh.N9yndZaJ.jLUpLAgF4kekaqdjhHAWJ8Y20AbG5T6'); // password: user123
        $user->setIsVerified(true);
        $manager->persist($user);

        // Create sample products
        $products = [
            [
                'name' => 'Wireless Headphones',
                'price' => 99.99,
                'image' => 'https://via.placeholder.com/300x200?text=Headphones',
                'category' => 'Electronics'
            ],
            [
                'name' => 'Smart Watch',
                'price' => 199.99,
                'image' => 'https://via.placeholder.com/300x200?text=Smart+Watch',
                'category' => 'Electronics'
            ],
            [
                'name' => 'Running Shoes',
                'price' => 79.99,
                'image' => 'https://via.placeholder.com/300x200?text=Shoes',
                'category' => 'Sports'
            ],
            [
                'name' => 'Coffee Maker',
                'price' => 149.99,
                'image' => 'https://via.placeholder.com/300x200?text=Coffee+Maker',
                'category' => 'Home'
            ],
            [
                'name' => 'Gaming Mouse',
                'price' => 49.99,
                'image' => 'https://via.placeholder.com/300x200?text=Gaming+Mouse',
                'category' => 'Electronics'
            ],
            [
                'name' => 'Yoga Mat',
                'price' => 29.99,
                'image' => 'https://via.placeholder.com/300x200?text=Yoga+Mat',
                'category' => 'Sports'
            ],
            [
                'name' => 'Bluetooth Speaker',
                'price' => 69.99,
                'image' => 'https://via.placeholder.com/300x200?text=Speaker',
                'category' => 'Electronics'
            ],
            [
                'name' => 'Water Bottle',
                'price' => 19.99,
                'image' => 'https://via.placeholder.com/300x200?text=Water+Bottle',
                'category' => 'Sports'
            ]
        ];

        foreach ($products as $productData) {
            $product = new \App\Entity\Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setImage($productData['image']);
            $product->setCategory($categoryEntities[$productData['category']]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
