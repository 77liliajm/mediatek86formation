<?php

namespace App\Tests\Integration;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    private FormationRepository $repo;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repo = static::getContainer()->get(FormationRepository::class);
    }

    public function testFindAllOrderByTitleAsc(): void
    {
        $formations = $this->repo->findAllOrderBy('title', 'ASC');
        $this->assertIsArray($formations);
    }

    public function testFindByContainValueVide(): void
    {
        $formations = $this->repo->findByContainValue('title', '');
        $this->assertIsArray($formations);
    }

    public function testFindByContainValueAvecValeur(): void
    {
        $formations = $this->repo->findByContainValue('title', 'zzz_inexistant');
        $this->assertIsArray($formations);
        $this->assertCount(0, $formations);
    }

    public function testFindAllLasted(): void
    {
        $formations = $this->repo->findAllLasted(3);
        $this->assertIsArray($formations);
        $this->assertLessThanOrEqual(3, count($formations));
    }
}

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

