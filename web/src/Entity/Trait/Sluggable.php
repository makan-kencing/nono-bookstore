<?php

namespace App\Entity\Trait;

trait Sluggable
{
    private string $slug {
        get => $this->slug;
        set => $this->slug;
    }
}