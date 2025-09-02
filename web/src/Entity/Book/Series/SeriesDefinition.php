<?php

namespace App\Entity\Book\Series;

use App\Entity\Book\Book;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class SeriesDefinition extends Entity
{
    #[Id]
    #[OneToOne]
    public Book $book;

    #[ManyToOne]
    public Series $series;

    public string $position;

    public int $seriesOrder;
}
