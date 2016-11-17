<?php

namespace MarcoTisi\Unifiables\Test;

use Illuminate\Database\Eloquent\Collection;
use MarcoTisi\Unifiables\Test\Models\EventTest;
use MarcoTisi\Unifiables\Test\Models\NewsTest;
use MarcoTisi\Unifiables\Unifiable;

class UnifiableTest extends TestCase
{
    public function testCreationOfInstance()
    {
        $this->assertInstanceOf(Unifiable::class, new Unifiable());
    }

    public function testBootWithUnifiables()
    {
        $focused = new FocusedTest();

        $this->assertArrayHasKey('MarcoTisi\Unifiables\Test\Models\NewsTest', $focused->getUnifiables());
        $this->assertArrayHasKey('MarcoTisi\Unifiables\Test\Models\EventTest', $focused->getUnifiables());
    }

    public function testAddUnifiable()
    {
        $unifiable = new Unifiable();

        $unifiable->addUnifiable(NewsTest::class, [
            'date' => 'published_at',
        ]);

        $unifiable->addUnifiable(EventTest::class, [
            'title'    => 'name',
            'subtitle' => 'venue',
            'date'     => 'held_at',
        ]);

        $this->assertArrayHasKey('MarcoTisi\Unifiables\Test\Models\NewsTest', $unifiable->getUnifiables());
        $this->assertArrayHasKey('MarcoTisi\Unifiables\Test\Models\EventTest', $unifiable->getUnifiables());
    }

    public function testResults()
    {
        $unifiable = new Unifiable();

        $unifiable->addUnifiable(NewsTest::class, [
            'date' => 'published_at',
        ]);

        $unifiable->addUnifiable(EventTest::class, [
            'title'    => 'name',
            'subtitle' => 'venue',
            'date'     => 'held_at',
        ]);

        $this->assertInstanceOf(Collection::class, $unifiable->get());
    }
}
