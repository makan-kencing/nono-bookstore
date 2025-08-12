<?php

namespace App\Entity\Book\Series;

use App\Entity\ABC\AssociativeEntity;
use App\Entity\Book\Book;

class SeriesDefinition extends AssociativeEntity
{
    public Book $book;
    public Series $series;
    public string $position;
    public int $seriesOrder;
}
