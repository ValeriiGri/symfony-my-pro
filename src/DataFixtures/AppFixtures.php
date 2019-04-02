<?php

namespace App\DataFixtures;



use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Cocur\Slugify\Slugify;

class AppFixtures extends Fixture
{
    private $faker;
    private $slug;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->slug = Slugify::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadPosts($manager);
    }

    public function loadPosts(ObjectManager $manager){
        for ($i = 1; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle($this->faker->text(100));
            $post->setSlug($this->slug->slugify($post->getTitle()));
            $post->setBody($this->faker->text(1000));
            $post->setCreatedAt($this->faker->dateTime);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
