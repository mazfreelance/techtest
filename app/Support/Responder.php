<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class Responder
{
    /**
     * Success
     *
     * @param object|array $data
     * @param string|null $message
     * @param integer $code
     * @return JsonResponse
     */
    public static function success($data = [], ?string $message = null, int $code = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Not Found
     *
     * @return JsonResponse
     */
    public static function notFound(?string $message = null): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_NOT_FOUND,
            'message' => $message ?? __('message.no_record'),
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Input Error
     *
     * @param array $errors
     * @return JsonResponse
     */
    public static function inputError(array $errors): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message' => __('message.invalid_input'),
            'errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Server Error
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => App::environment('production') || empty($message) ? __('message.server_error') : $message,
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Server Busy
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverBusy(): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_SERVICE_UNAVAILABLE,
            'message' => __('message.server_busy'),
        ], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * Method Not Allowed
     *
     * @return JsonResponse
     */
    public static function methodNotAllowed(): JsonResponse
    {
        return response()->json([
            'code' => JsonResponse::HTTP_METHOD_NOT_ALLOWED,
            'message' => __('message.method_not_allowed'),
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Error
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function error(string $message, int $code = JsonResponse::HTTP_BAD_REQUEST, int $errorNo = 0): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'error' => $errorNo,
            'message' => $message,
        ], $code);
    }

    /**
     * Unauthorized
     *
     * @return JsonResponse
     */
    public static function unauthorized(): JsonResponse
    {
        return self::error(__('message.unauthorized'), JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * Forbidden Access
     *
     * @return JsonResponse
     */
    public static function forbiddenAccess(): JsonResponse
    {
        return self::error(__('message.no_permission_access'), JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * Forbidden Manage
     *
     * @return JsonResponse
     */
    public static function forbiddenManage(): JsonResponse
    {
        return self::error(__('message.no_permission_manage'), JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * Forbidden Action
     *
     * @return JsonResponse
     */
    public static function forbiddenAction(): JsonResponse
    {
        return self::error(__('message.no_permission_perform_action'), JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * Forbidden Login
     *
     * @return JsonResponse
     */
    public static function forbiddenLogin(): JsonResponse
    {
        return self::error(__('message.no_permission_login'), JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * Too Many Attempts
     *
     * @return JsonResponse
     */
    public static function tooManyAttempts(): JsonResponse
    {
        return self::error(__('message.too_many_attempts'), JsonResponse::HTTP_TOO_MANY_REQUESTS);
    }
}
