<?php

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieImage;

final class Image
{
    private string $url;

    public function __construct(string $url)
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
