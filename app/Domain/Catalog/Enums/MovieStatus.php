<?php

/**
 * Enum que representa los posibles estados de una película en el sistema.
 *
 * Define el ciclo de vida de una película:
 * - DRAFT: Película en modo borrador, aún no publicada
 * - PUBLISHED: Película publicada y disponible para visualización
 * - ARCHIVED: Película archivada, ya no disponible públicamente
 *
 * Este enum se utiliza para controlar la visibilidad y disponibilidad
 * de las películas en el catálogo del cine.
 */

namespace App\Domain\Catalog\Enums;

enum MovieStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
