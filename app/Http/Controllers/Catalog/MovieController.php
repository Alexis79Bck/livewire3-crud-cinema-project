<?php

namespace App\Http\Controllers\Catalog;

use App\Application\Catalog\Movie\Commands\ArchiveMovieCommand;
use App\Application\Catalog\Movie\Commands\CreateMovieCommand;
use App\Application\Catalog\Movie\Commands\PublishMovieCommand;
use App\Application\Catalog\Movie\Handlers\ArchiveMovieHandler;
use App\Application\Catalog\Movie\Handlers\CreateMovieHandler;
use App\Application\Catalog\Movie\Handlers\PublishMovieHandler;
use App\Http\Controllers\Controller;
use DateTimeImmutable;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function store(
        Request $request,
        CreateMovieHandler $handler
    ) {

        $command = new CreateMovieCommand(
            $request->input('title'),
            $request->input('plot'),
            new DateTimeImmutable($request->input('release_date')),
            $request->input('rating'),
            $request->input('image')
        );

        $handler->handle($command);

        return response()->json([
            'message' => 'Movie created successfully'
        ], 201);
    }

    public function publish(
        string $id,
        PublishMovieHandler $handler
    ) {

        $command = new PublishMovieCommand($id);

        $handler->handle($command);

        return response()->json([
            'message' => 'Movie published'
        ], 200);
    }

    public function archive(
        string $id,
        ArchiveMovieHandler $handler
    ) {

        $command = new ArchiveMovieCommand($id);

        $handler->handle($command);

        return response()->json([
            'message' => 'Movie archived'
        ], 200);
    }
}
