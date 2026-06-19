<?php

namespace App\View\Composers;

use App\Services\Homepage;
use Roots\Acorn\View\Composer;

class Index extends Composer
{
    protected static $views = [
        'index',
    ];

    public function with(): array
    {
        $homepage = new Homepage();

        return [
            'sections' => $homepage->loadSections(),
        ];
    }
}
