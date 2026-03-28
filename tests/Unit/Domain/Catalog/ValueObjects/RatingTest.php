<?php

namespace Tests\Unit\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieRating;
use App\Domain\Catalog\ValueObjects\Rating;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    /** @test */
    public function it_can_create_valid_ratings(): void
    {
        $validRatings = ['G', 'PG', 'PG-13', 'R', 'NC-17'];

        foreach ($validRatings as $validRating) {
            $rating = new Rating($validRating);
            $this->assertInstanceOf(Rating::class, $rating);
            $this->assertEquals($validRating, $rating->value());
        }
    }

    /** @test */
    public function it_converts_rating_to_uppercase(): void
    {
        $rating = new Rating('pg');

        $this->assertEquals('PG', $rating->value());
    }

    /** @test */
    public function it_trims_whitespace_from_rating(): void
    {
        $rating = new Rating('  R  ');

        $this->assertEquals('R', $rating->value());
    }

    /** @test */
    public function it_cannot_create_invalid_rating(): void
    {
        $this->expectException(InvalidMovieRating::class);

        new Rating('INVALID');
    }

    /** @test */
    public function it_cannot_create_empty_rating(): void
    {
        $this->expectException(InvalidMovieRating::class);

        new Rating('');
    }

    /** @test */
    public function it_can_compare_ratings(): void
    {
        $rating1 = new Rating('R');
        $rating2 = new Rating('R');
        $rating3 = new Rating('PG');

        $this->assertTrue($rating1->equals($rating2));
        $this->assertFalse($rating1->equals($rating3));
    }

    /** @test */
    public function it_can_convert_to_string(): void
    {
        $rating = new Rating('PG-13');

        $this->assertEquals('PG-13', (string) $rating);
    }
}
