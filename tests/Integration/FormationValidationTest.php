<?php

namespace App\Tests\Integration;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    public function testDateValide(): void
    {
        $formation = new Formation();
        $formation->setTitle('Test');
        $formation->setPublishedAt(new \DateTime('2024-01-15'));

        $errors = $this->validator->validate($formation);
        $this->assertCount(0, $errors);
    }

    public function testDateFuturInvalide(): void
    {
        $formation = new Formation();
        $formation->setTitle('Test');
        $formation->setPublishedAt(new \DateTime('+1 day'));

        $errors = $this->validator->validate($formation);
        $this->assertGreaterThan(0, count($errors));
    }
}

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

