<?php

namespace App\Entity\Book\Series;

use App\Entity\ABC\Entity;
use App\Entity\ABC\Trait\Commentable;
use App\Entity\Book\Book;

class SeriesDefinition extends Entity
{
    public Book $book;
    public Series $series;
    public string $position;
    public int $seriesOrder;
}
