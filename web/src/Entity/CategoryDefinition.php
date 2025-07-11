<?php

namespace App\Entity;

use App\Entity\Trait\Commetable;
use App\Entity\Trait\TimeLimited;

class CategoryDefinition
{
    use TimeLimited, Commetable;

    private Category $category {
        get => $this->category;
        set => $this->category;
    }
}