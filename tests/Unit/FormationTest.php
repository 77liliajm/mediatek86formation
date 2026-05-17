<?php

namespace App\Tests\Unit;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtStringAvecDate(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2024-01-15'));
        $this->assertEquals('15/01/2024', $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringSansDate(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(null);
        $this->assertEquals('', $formation->getPublishedAtString());
    }
}

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

