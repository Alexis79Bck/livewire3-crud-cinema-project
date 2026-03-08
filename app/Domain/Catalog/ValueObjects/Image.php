<?php

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieImage;

/**
 * Value Object que representa la imagen (póster) de una película.
 *
 * Encapsula la URL de la imagen de una película, asegurando que sea una URL válida
 * y que apunte a un archivo de imagen permitido (jpg, jpeg, png, webp).
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 *
 * @see InvalidMovieImage Excepción lanzada cuando la URL es inválida
 */
final class Image
{

    public function __construct(private string $url)
    {
        if (!$this->filterValidUrl($url)) {
            throw InvalidMovieImage::invalidUrl();
        }

        $this->url = $url;
    }

    public function value(): string
    {
        return $this->url;
    }

    private function filterValidUrl(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $extension = pathinfo($url, PATHINFO_EXTENSION);

        return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'], true);
    }
}
