<?php

namespace Tests\Unit\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieTitle;
use App\Domain\Catalog\ValueObjects\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    /** @test */
    public function it_can_create_a_title(): void
    {
        $title = new Title('The Matrix');

        $this->assertInstanceOf(Title::class, $title);
        $this->assertEquals('The Matrix', $title->value());
    }

    /** @test */
    public function it_trims_whitespace(): void
    {
        $title = new Title('  The Matrix  ');

        $this->assertEquals('The Matrix', $title->value());
    }

    /** @test */
    public function it_cannot_create_a_title_with_empty_string(): void
    {
        $this->expectException(InvalidMovieTitle::class);

        new Title('');
    }

    /** @test */
    public function it_cannot_create_a_title_with_only_whitespace(): void
    {
        $this->expectException(InvalidMovieTitle::class);

        new Title('   ');
    }

    /** @test */
    public function it_cannot_create_a_title_longer_than_255_characters(): void
    {
        $this->expectException(InvalidMovieTitle::class);

        new Title(str_repeat('a', 256));
    }

    /** @test */
    public function it_can_create_a_title_with_exactly_255_characters(): void
    {
        $title = new Title(str_repeat('a', 255));

        $this->assertInstanceOf(Title::class, $title);
        $this->assertEquals(255, strlen($title->value()));
    }

    /** @test */
    public function it_can_compare_titles(): void
    {
        $title1 = new Title('The Matrix');
        $title2 = new Title('The Matrix');
        $title3 = new Title('Inception');

        $this->assertTrue($title1->equals($title2));
        $this->assertFalse($title1->equals($title3));
    }

    /** @test */
    public function it_can_convert_to_string(): void
    {
        $title = new Title('The Matrix');

        $this->assertEquals('The Matrix', (string) $title);
    }
}
