<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponser {

    /**
     * Sends a success JSON response with the provided data and status code.
     *
     * @param mixed $data The data to include in the response
     * @param int $code The HTTP status code for the response
     * @return JsonResponse The JSON response object
     */
    private function successResponse(mixed $data, int $code): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Sends an error JSON response with the provided message and status code.
     *
     * @param string $message The error message to include in the response
     * @param int $code The HTTP status code for the response
     * @return JsonResponse The JSON response object containing the error message and code
     */
    protected function errorResponse(string $message, int $code): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Sends a JSON response with a collection of data and a status code.
     *
     * @param Collection $collection The collection of data to include in the response
     * @param int $code The HTTP status code for the response (default: Response::HTTP_OK)
     * @return JsonResponse The JSON response object
     */
    protected function showAll(Collection $collection, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse([
            'data' => $collection,
        ], $code);
    }

    /**
     * Returns a JSON response containing the provided model instance data and HTTP status code.
     *
     * @param Model $instance The instance of the model to include in the response
     * @param int $code The HTTP status code for the response (default: Response::HTTP_OK)
     * @return JsonResponse The JSON response object
     */
    protected function showOne(Model $instance, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse([
            'data' => $instance
        ], $code);
    }

    /**
     * Sends a JSON response with the provided message data and status code.
     *
     * @param mixed $data The data to include in the response
     * @param int $code The HTTP status code for the response (default: Response::HTTP_OK)
     * @return JsonResponse The JSON response object
     */
    protected function showMessage(mixed $data, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse([
            'data' => $data
        ], $code);
    }

}
