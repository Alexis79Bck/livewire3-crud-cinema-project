<?php

namespace Tests\Unit\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\Commands\CreateMovieCommand;
use App\Application\Catalog\Movie\Handlers\CreateMovieHandler;
use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Shared\Generator\IdGenerator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreateMovieHandlerTest extends TestCase
{
    private MovieRepository&MockObject $repository;
    private IdGenerator&MockObject $idGenerator;
    private CreateMovieHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MovieRepository::class);
        $this->idGenerator = $this->createMock(IdGenerator::class);
        $this->handler = new CreateMovieHandler($this->repository, $this->idGenerator);
    }

    /** @test */
    public function it_can_handle_create_movie_command(): void
    {
        $movieId = '550e8400-e29b-41d4-a716-446655440000';

        $this->idGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($movieId);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Movie::class));

        $command = new CreateMovieCommand(
            title: 'The Matrix',
            plot: 'A computer hacker learns about the true nature of reality',
            releaseDate: new \DateTimeImmutable('1999-03-31'),
            rating: 'R',
            image: 'https://example.com/matrix.jpg'
        );

        $this->handler->handle($command);
    }

    /** @test */
    public function it_creates_movie_with_correct_data(): void
    {
        $movieId = '550e8400-e29b-41d4-a716-446655440000';

        $this->idGenerator
            ->method('generate')
            ->willReturn($movieId);

        $savedMovie = null;
        $this->repository
            ->method('save')
            ->willReturnCallback(function (Movie $movie) use (&$savedMovie) {
                $savedMovie = $movie;
            });

        $command = new CreateMovieCommand(
            title: 'The Matrix',
            plot: 'A computer hacker learns about the true nature of reality',
            releaseDate: new \DateTimeImmutable('1999-03-31'),
            rating: 'R',
            image: 'https://example.com/matrix.jpg'
        );

        $this->handler->handle($command);

        $this->assertNotNull($savedMovie);
        $this->assertEquals($movieId, $savedMovie->id()->value());
        $this->assertEquals('The Matrix', $savedMovie->title()->value());
        $this->assertEquals('A computer hacker learns about the true nature of reality', $savedMovie->plot()->value());
        $this->assertEquals('R', $savedMovie->rating()->value());
        $this->assertEquals('https://example.com/matrix.jpg', $savedMovie->image()->value());
    }

    /** @test */
    public function it_generates_unique_id_for_each_movie(): void
    {
        $movieId1 = '550e8400-e29b-41d4-a716-446655440000';
        $movieId2 = '550e8400-e29b-41d4-a716-446655440001';

        $this->idGenerator
            ->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls($movieId1, $movieId2);

        $this->repository
            ->expects($this->exactly(2))
            ->method('save');

        $command1 = new CreateMovieCommand(
            title: 'The Matrix',
            plot: 'A computer hacker learns about the true nature of reality',
            releaseDate: new \DateTimeImmutable('1999-03-31'),
            rating: 'R',
            image: 'https://example.com/matrix.jpg'
        );

        $command2 = new CreateMovieCommand(
            title: 'Inception',
            plot: 'A thief who steals corporate secrets through dream-sharing technology',
            releaseDate: new \DateTimeImmutable('2010-07-16'),
            rating: 'PG-13',
            image: 'https://example.com/inception.jpg'
        );

        $this->handler->handle($command1);
        $this->handler->handle($command2);
    }
}
