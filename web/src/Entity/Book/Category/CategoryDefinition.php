<?php

declare(strict_types=1);

namespace App\Entity\Book\Category;

use App\Entity\Book\Work;
use App\Entity\Trait\Commentable;
use App\Entity\Trait\TimeLimited;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class CategoryDefinition extends Entity
{
    use Commentable;
    use TimeLimited;

    #[Id]
    #[ManyToOne]
    public Work $work;

    #[Id]
    #[ManyToOne]
    public Category $category;

    public bool $isPrimary = false;
}
