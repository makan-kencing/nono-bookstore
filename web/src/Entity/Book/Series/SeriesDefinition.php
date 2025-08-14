<?php

namespace App\Entity\Book\Series;

use App\Entity\Book\Book;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;
use App\Orm\MapsId;
use App\Orm\OneToOne;

class SeriesDefinition extends Entity
{
    #[Id]
    public ?int $bookId;

    #[MapsId]
    #[OneToOne]
    public Book $book;

    #[ManyToOne]
    public Series $series;

    public string $position;

    public int $seriesOrder;
}
