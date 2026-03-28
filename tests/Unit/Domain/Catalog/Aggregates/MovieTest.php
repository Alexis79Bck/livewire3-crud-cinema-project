<?php

namespace Tests\Unit\Domain\Catalog\Aggregates;

use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Enums\MovieStatus;
use App\Domain\Catalog\Events\MovieCreated;
use App\Domain\Catalog\Events\MoviePublished;
use App\Domain\Catalog\Events\MovieArchived;
use App\Domain\Catalog\Exceptions\InvalidMovieStatus;
use App\Domain\Catalog\ValueObjects\Image;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Plot;
use App\Domain\Catalog\ValueObjects\Rating;
use App\Domain\Catalog\ValueObjects\ReleaseDate;
use App\Domain\Catalog\ValueObjects\Title;
use PHPUnit\Framework\TestCase;

class MovieTest extends TestCase
{
    /** @test */
    public function it_can_create_a_movie(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $movie->id()->value());
        $this->assertEquals('The Matrix', $movie->title()->value());
        $this->assertEquals(MovieStatus::DRAFT, $movie->status());
    }

    /** @test */
    public function it_publishes_movie_created_event_when_created(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $events = $movie->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MovieCreated::class, $events[0]);
    }

    /** @test */
    public function it_can_publish_a_draft_movie(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->publish();

        $this->assertEquals(MovieStatus::PUBLISHED, $movie->status());
    }

    /** @test */
    public function it_publishes_movie_published_event_when_published(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->clearEvents();
        $movie->publish();

        $events = $movie->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MoviePublished::class, $events[0]);
    }

    /** @test */
    public function it_cannot_publish_an_already_published_movie(): void
    {
        $this->expectException(InvalidMovieStatus::class);

        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->publish();
        $movie->publish(); // Should throw exception
    }

    /** @test */
    public function it_cannot_publish_an_archived_movie(): void
    {
        $this->expectException(InvalidMovieStatus::class);

        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->archive();
        $movie->publish(); // Should throw exception
    }

    /** @test */
    public function it_can_archive_a_movie(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->archive();

        $this->assertEquals(MovieStatus::ARCHIVED, $movie->status());
    }

    /** @test */
    public function it_publishes_movie_archived_event_when_archived(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->clearEvents();
        $movie->archive();

        $events = $movie->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MovieArchived::class, $events[0]);
    }

    /** @test */
    public function it_can_check_valid_state_transitions(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        // DRAFT can transition to PUBLISHED
        $this->assertTrue($movie->canTransitionTo(MovieStatus::PUBLISHED));

        // DRAFT can transition to ARCHIVED
        $this->assertTrue($movie->canTransitionTo(MovieStatus::ARCHIVED));

        // DRAFT cannot transition to DRAFT
        $this->assertFalse($movie->canTransitionTo(MovieStatus::DRAFT));
    }

    /** @test */
    public function published_movie_can_only_transition_to_archived(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->publish();

        // PUBLISHED can transition to ARCHIVED
        $this->assertTrue($movie->canTransitionTo(MovieStatus::ARCHIVED));

        // PUBLISHED cannot transition to DRAFT
        $this->assertFalse($movie->canTransitionTo(MovieStatus::DRAFT));

        // PUBLISHED cannot transition to PUBLISHED
        $this->assertFalse($movie->canTransitionTo(MovieStatus::PUBLISHED));
    }

    /** @test */
    public function archived_movie_cannot_transition_to_any_state(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $movie->archive();

        // ARCHIVED cannot transition to DRAFT
        $this->assertFalse($movie->canTransitionTo(MovieStatus::DRAFT));

        // ARCHIVED cannot transition to PUBLISHED
        $this->assertFalse($movie->canTransitionTo(MovieStatus::PUBLISHED));

        // ARCHIVED cannot transition to ARCHIVED
        $this->assertFalse($movie->canTransitionTo(MovieStatus::ARCHIVED));
    }

    /** @test */
    public function it_can_release_and_clear_events(): void
    {
        $movie = Movie::create(
            new MovieId('550e8400-e29b-41d4-a716-446655440000'),
            new Title('The Matrix'),
            new Plot('A computer hacker learns about the true nature of reality'),
            new ReleaseDate(new \DateTimeImmutable('1999-03-31')),
            new Rating('R'),
            new Image('https://example.com/matrix.jpg')
        );

        $this->assertCount(1, $movie->getEvents());

        $releasedEvents = $movie->releaseEvents();
        $this->assertCount(1, $releasedEvents);
        $this->assertCount(0, $movie->getEvents());

        $movie->publish();
        $this->assertCount(1, $movie->getEvents());

        $movie->clearEvents();
        $this->assertCount(0, $movie->getEvents());
    }
}
