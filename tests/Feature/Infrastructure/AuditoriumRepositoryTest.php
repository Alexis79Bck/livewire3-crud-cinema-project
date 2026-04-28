<?php

namespace Tests\Feature\Infrastructure;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;
use App\Domain\Theater\Repositories\AuditoriumRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditoriumRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AuditoriumRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(AuditoriumRepository::class);
    }

    /** @test */
    public function it_can_save_and_find_an_auditorium_by_id()
    {
        // Arrange
        $id = AuditoriumId::generate();
        $auditorium = Auditorium::create(
            $id,
            'Main Hall',
            150,
            'First Floor'
        );

        // Act
        $this->repository->save($auditorium);
        $found = $this->repository->findById($id);

        // Assert
        $this->assertNotNull($found);
        $this->assertEquals($id->value(), $found->id()->value());
        $this->assertEquals('Main Hall', $found->name());
        $this->assertEquals(150, $found->capacity());
        $this->assertEquals('First Floor', $found->location());
        $this->assertEquals(AuditoriumStatus::ACTIVE, $found->status());
    }

    /** @test */
    public function it_can_find_all_auditoriums()
    {
        // Arrange
        $auditorium1 = Auditorium::create(
            AuditoriumId::generate(),
            'Main Hall',
            150,
            'First Floor'
        );

        $auditorium2 = Auditorium::create(
            AuditoriumId::generate(),
            'Premium Theater',
            80,
            'Second Floor'
        );

        $this->repository->save($auditorium1);
        $this->repository->save($auditorium2);

        // Act
        $all = $this->repository->findAll();

        // Assert
        $this->assertCount(2, $all);
        $this->assertTrue(
            collect($all)->contains(
                fn($auditorium) => $auditorium->name() === 'Main Hall'
            )
        );
        $this->assertTrue(
            collect($all)->contains(
                fn($auditorium) => $auditorium->name() === 'Premium Theater'
            )
        );
    }

    /** @test */
    public function it_can_find_auditoriums_by_status()
    {
        // Arrange
        $activeAuditorium = Auditorium::create(
            AuditoriumId::generate(),
            'Main Hall',
            150,
            'First Floor'
        );

        $maintenanceAuditorium = Auditorium::create(
            AuditoriumId::generate(),
            'Backup Theater',
            100,
            'Basement'
        );

        $maintenanceAuditorium->changeStatus(AuditoriumStatus::MAINTENANCE);

        $this->repository->save($activeAuditorium);
        $this->repository->save($maintenanceAuditorium);

        // Act
        $activeOnes = $this->repository->findByStatus(AuditoriumStatus::ACTIVE);
        $maintenanceOnes = $this->repository->findByStatus(AuditoriumStatus::MAINTENANCE);

        // Assert
        $this->assertCount(1, $activeOnes);
        $this->assertCount(1, $maintenanceOnes);
        $this->assertEquals('Main Hall', $activeOnes[0]->name());
        $this->assertEquals('Backup Theater', $maintenanceOnes[0]->name());
    }
}