<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/series')]
readonly class SeriesController extends Controller
{
    /**
     * @param string $slug
     * @return void
     */
    #[GET]
    #[Path('/{slug}')]
    public function viewSeries(string $slug): void
    {
        echo $this->render('series.php');
    }
}
