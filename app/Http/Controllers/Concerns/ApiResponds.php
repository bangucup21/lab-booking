<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;

/**
 * Format respons JSON konsisten untuk semua controller yang dikonsumsi
 * React tanpa reload. Dipakai oleh AssignmentPublicController (siswa)
 * dan AssignmentAdminController (guru) supaya frontend cukup pegang
 * satu kontrak: { success, message, data|errors }.
 */
trait ApiResponds
{
    protected function ok(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * @param array<string, array<int, string>> $errors Format sama seperti $validator->errors()->toArray(),
     *                                                   supaya gampang dipetakan ke form React (errors.field[0]).
     */
    protected function fail(string $message, int $status = 422, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
