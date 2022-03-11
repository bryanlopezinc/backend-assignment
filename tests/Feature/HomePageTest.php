<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function testWillReturnNotFoundResponse(): void
    {
        $this->get('/')->assertNotFound();
        $this->getJson('/')->assertNotFound();
    }
}
