<?php

namespace MarcoTisi\Unifiables\Test;

use MarcoTisi\Unifiables\Test\Models\EventTest;
use MarcoTisi\Unifiables\Test\Models\NewsTest;
use MarcoTisi\Unifiables\Unifiable;

class UnifiableTest extends TestCase
{
    public function testCreationOfInstance()
    {
        $this->assertInstanceOf(Unifiable::class, new Unifiable());
    }

    public function testUnifiable()
    {
        Unifiable::addUnifiable(NewsTest::class, [
            'date' => 'published_at',
        ]);

        Unifiable::addUnifiable(EventTest::class, [
            'title'    => 'name',
            'subtitle' => 'venue',
            'date'     => 'held_at',
        ]);

        $this->assertEquals(100, Unifiable::count());
    }
}
