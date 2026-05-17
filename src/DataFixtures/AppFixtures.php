<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@mediatek86.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('$2y$13$jt0xcUnPV5oCbFfXmTltWufI7dRnfLmTrmT5GTyhS8EuOPuUNVFkq');

        $manager->persist($user);
        $manager->flush();
    }
}