<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Produit;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $images =[
            'chaise.jpg',
            'hamac.jpg',
            'parasol.jpg',
            'piscine.jpg',
            'salon-de-jardin1561969249.jpeg',
            'seau-de-plage1561969551.jpeg',
            'table1561727671.jpeg',
            'volley.jpg'
        ];
        //Creation du Faker (generateur de donnees)
        $generator = Factory::create('fr_FR');
        //Populateur d'entites (se base sur src/Entity)
        $populator = new Populator($generator, $manager);
        // Creation des categories
        $populator->addEntity(Category::class, 10);
        $populator->addEntity(Tag::class, 20);
        $populator->addEntity(User::class, 20);
        $populator->addEntity(Produit::class, 197, [
            'price'=>function () use ($generator) {
                return $generator->randomFloat(2, 0, 9999999.99);
            },
            'imageName' => function () use ($images) {
                return $images[rand(0, sizeof($images)-1)];
            }
        ]);
        //FLUSH
        $populator->execute();
    }
}
