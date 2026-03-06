<?php

namespace App\Domain\Catalog\Aggregates\Movies;

enum MovieStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
