<?php

namespace App\DataFixtures;

use App\Entity\Constructor;
use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private array $constructors = [
        'Pear',
        'Sumsing'
    ];

    private array $models = [
        [1, 'Jphone 10','un téléphone bien puissant avec une autonomie améliorée par rapport à ses prédessesseurs', 1249.99],
        [2, 'Universe T2','L\'un des meilleurs appareil photo du marché',899.99],
        [2, 'Universe B60','Un téléphone bien adapté pour le jeu',759.99]
    ];

    // mdp1 : test1, mdp2 : test2
    private array $customers = [
        ['Danube Marketplace','bileMoCust1','$2y$13$RursWsNq15X54IoUfgAOuusAQ3H89BXAad/dAiG5YwB37T0Dr5r2O', ['ROLE_USER']],
        ['Green telecom','bileMoCust2','$2y$13$YMA1FOOMCNym09LuanskZuOzuzVu/2DbvBPrjxFxlMTaYWbyLX62a', ['ROLE_USER']]
    ];

    public function load(ObjectManager $manager)
    {
        // constructors
        foreach ($this->constructors as $constructor) {
            $newConstructor = new Constructor();
            $newConstructor->setName($constructor);
            $manager->persist($newConstructor);
        }
        $manager->flush();
        // products
        foreach ($this->models as $model) {
            list($constructor, $name, $description, $price) = $model;
            $newProduct = new Product();
            $newProduct
            ->setName($name)
            ->setPrice($price)
            ->setDescription($description)
            ->setConstructor(
                $manager->find(Constructor::class, $constructor)
            );
            $manager->persist($newProduct);
        }

        $manager->flush();

        foreach ($this->customers as $customer) {
            list($name, $username, $password, $role) = $customer;
            $newCustomer = new Customer();
            $newCustomer->setName($name)->setUserName($username)->setPassword($password)->setRoles($role);
            $manager->persist($newCustomer);
        }
        $manager->flush();
    }
}
