<?php


namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;


trait HasApiJsonResponse
{
    public static function successfulResponse(int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['status' => 'success'], $status, [], JSON_UNESCAPED_UNICODE);
    }

    public static function successfulResponseWithData(mixed $data, int $status = null): JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_OK;
        }

        if (is_array($data) || $data instanceof Model || $data instanceof LengthAwarePaginator|| $data instanceof Collection) {
            $data = self::convertToCamelCase($data);
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ],
            $status,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function redirect(string $route, int $status = null, array $headers = []): JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_SEE_OTHER;
        }

        $response = \response();
        if (!empty($headers)) {
            $response->withHeaders($headers);
        }

        return $response->json(
            [
                'status' => 'success',
                'data' => [
                    'action' => 'redirect',
                    'url'    => $route
                ]
            ],
            $status,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function errorResponse(string $message, int $status = null): JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => $message
            ],
            $status,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function errorResponseWithData(string $message, mixed $data, int $status = null): JsonResponse
    {
        if ($status === null) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => $message,
                'data' => $data
            ],
            $status,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function validationErrorResponse(string $errorKey, array $errorMessages): JsonResponse
    {
        return response()->json(
            [
                'message' => __('invalid data entered'),
                'errors' => [
                    $errorKey => $errorMessages
                ],
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Convert snake case array keys to camel case array for js readable response.
     *
     * @param mixed $data
     * @return array
     */
    public static function convertToCamelCase(array|Model|LengthAwarePaginator|Collection $data)
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }
        $converted = [];
        foreach ($data as $key => $value) {
            $key = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $key[0] = strtolower($key[0]);
            if (is_array($value) || $value instanceof Model) {
                $value = self::convertToCamelCase($value);
            }
            $converted[$key] = $value;
        }
        return $converted;
    }
}
