<?php

declare(strict_types=1);

namespace App\Controller;

readonly class SeriesController extends Controller
{
    /**
     * @param array<string, string> $pathVars
     * @return void
     */
    public function viewSeries(array $pathVars): void
    {
        $slug = $pathVars['slug'];

        echo $this->render('series.php');
    }
}
