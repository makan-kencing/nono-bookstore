<?php

namespace App\Entity\Book\Series;

use App\Entity\Book\Work;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class SeriesDefinition extends Entity
{
    #[Id]
    #[OneToOne]
    public Work $work;

    #[ManyToOne]
    public Series $series;

    public string $position;
}
