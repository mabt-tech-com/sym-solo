<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description for product 1',
                'prix' => 10.99,
                'image' => 'product1.jpg',
                'product_points' => 10,
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for product 2',
                'prix' => 20.99,
                'image' => 'product2.jpg',
                'product_points' => 20,
            ],
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setDescription($productData['description']);
            $product->setPrix($productData['prix']);
            $product->setImage($productData['image']);
            $product->setProductPoints($productData['product_points']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}