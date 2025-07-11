<?php

namespace App\Entity\Book\Category;

use App\Entity\ABC\Trait\Commetable;
use App\Entity\ABC\Trait\TimeLimited;

class CategoryDefinition
{
    use TimeLimited, Commetable;

    private Category $category {
        get => $this->category;
        set => $this->category;
    }
}