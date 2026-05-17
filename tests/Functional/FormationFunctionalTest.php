<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationFunctionalTest extends WebTestCase
{
    public function testPageAccueilAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testPageFormationsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    public function testPagePlaylistsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminRedirigeVersLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
    }

    public function testTriFormationsTitre(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        $this->assertResponseIsSuccessful();
    }

    public function testTriFormationsDate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertResponseIsSuccessful();
    }
}

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

