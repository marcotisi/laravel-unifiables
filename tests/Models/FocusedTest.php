<?php

namespace MarcoTisi\Unifiables\Test;

use MarcoTisi\Unifiables\Test\Models\EventTest;
use MarcoTisi\Unifiables\Test\Models\NewsTest;
use MarcoTisi\Unifiables\Unifiable;

class FocusedTest extends Unifiable
{
    protected static $unifiableFields = [
        'title',
        'subtitle',
        'date',
    ];

    protected static $unifiables = [
        EventTest::class => [
            'date' => 'published_at',
        ],
        NewsTest::class  => [
            'title'    => 'name',
            'subtitle' => 'venue',
            'date'     => 'held_at',
        ],
    ];
}
