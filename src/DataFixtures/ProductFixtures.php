<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $products = [
            [
                'name' => 'Test 3',
                'image' => 'https://source.unsplash.com/random',
                'price' => '142.00',
                'description' => 'Test description',
            ],
            [
                'name' => 'Test 2',
                'image' => 'https://source.unsplash.com/random',
                'price' => '160.00',
                'description' => 'lorem ipsum',
            ],
            [
                'name' => 'Test 1',
                'image' => 'https://source.unsplash.com/random',
                'price' => '445.93',
                'description' => 'lorem ipsum',
            ]
        ];

        foreach ($products as $productData){
            $product = new Product();
            $product
                ->setName($productData['name'])
                ->setImage($productData['image'])
                ->setDescription($productData['description'])
                ->setPrice(floatval($productData['price']));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
