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

/**
 * Controlador del Bounded Context Catalog
 *
 * Maneja las solicitudes HTTP relacionadas con la gestión del catálogo de películas.
 * Implementa el patrón de controlador en la capa de infraestructura de la arquitectura limpia.
 *
 * Este controlador expone endpoints REST para crear, publicar y archivar películas
 * dentro del contexto delimitado de Catalog del sistema de cinema.
 *
 * @package App\Http\Controllers\Catalog
 * @subpackage Movie Management
 */
class MovieController extends Controller
{
    /**
     * Crea una nueva película en el catálogo.
     *
     * Recibe los datos de la película (título, trama, fecha de estreno, clasificación,
     * imagen) y los procesa mediante el handler CreateMovieCommand para persistir
     * la entidad en el repositorio.
     *
     * @param Request $request Objeto de solicitud HTTP con los datos de la película
     * @param CreateMovieHandler $handler Manejador del comando de creación
     * @return \Illuminate\Http\JsonResponse Respuesta con mensaje de éxito
     */
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

    /**
     * Publica una película en el catálogo.
     *
     * Cambia el estado de la película a 'publicada', haciéndola visible
     * y disponible para su visualización en el sistema.
     *
     * @param string $id Identificador único de la película a publicar
     * @param PublishMovieHandler $handler Manejador del comando de publicación
     * @return \Illuminate\Http\JsonResponse Respuesta con mensaje de éxito
     */
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

    /**
     * Archiva una película del catálogo.
     *
     * Cambia el estado de la película a 'archivada', removiéndola de la
     * vista pública pero manteniendo los datos en el sistema para referencia histórica.
     *
     * @param string $id Identificador único de la película a archivar
     * @param ArchiveMovieHandler $handler Manejador del comando de archivado
     * @return \Illuminate\Http\JsonResponse Respuesta con mensaje de éxito
     */
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
