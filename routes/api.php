<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\AssignmentAdminController;
use App\Http\Controllers\AssignmentPublicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
|--------------------------------------------------------------------------
| BOT API ROUTES
| Semua route di sini dilindungi BotAuthMiddleware
| Bot Python wajib kirim: Authorization: Bearer <BOT_TOKEN>
|--------------------------------------------------------------------------
*/
Route::prefix('bot')->middleware('bot.auth')->group(function () {

    // --- Identity ---
    Route::post('identify', [BotController::class, 'identify']);

    // --- Jadwal ---
    Route::get('jadwal/hari-ini',   [BotController::class, 'jadwalHariIni']);
    Route::get('jadwal/minggu-ini', [BotController::class, 'jadwalMingguIni']);

    // --- Booking ---
    Route::get('booking/pending',           [BotController::class, 'bookingPending']);
    Route::get('booking/hari-ini',          [BotController::class, 'bookingHariIni']);
    Route::post('booking/create',           [BotController::class, 'bookingCreate']);
    Route::post('booking/{id}/approve',     [BotController::class, 'bookingApprove']);
    Route::post('booking/{id}/reject',      [BotController::class, 'bookingReject']);

    // --- Labs & Slots ---
    Route::get('labs',                      [BotController::class, 'labs']);
    Route::get('labs/{id}/slots',           [BotController::class, 'labSlots']);

    // --- Sesi ---
    Route::get('sesi/aktif',               [BotController::class, 'sesiAktif']);
});

/*
|--------------------------------------------------------------------------
| ASSIGNMENT DASHBOARD ROUTES (React) — siswa & guru
|--------------------------------------------------------------------------
| Dibungkus middleware('web') secara EKSPLISIT — supaya session PIN siswa
| / token guru kebaca (grup 'api' bawaan Laravel itu stateless, tidak ada
| session). Middleware 'web' di sini DITAMBAHKAN di atas middleware 'api'
| yang sudah otomatis berlaku untuk seluruh file ini, bukan menggantikannya.
|
| JANGAN tambah ->prefix('api') — Laravel sudah otomatis menambahkan /api
| di depan semua route di file ini (lihat route bot di atas: prefix('bot')
| hasil akhirnya /api/bot/...).
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {

    // ══════════════════════════════════════════════════════════════
    // SISWA — hasil akhir: /api/tugas/..., guard: PIN kelas di session
    // ══════════════════════════════════════════════════════════════

    Route::prefix('tugas')->name('api.assignment.')->group(function () {
        Route::get('/', [AssignmentPublicController::class, 'indexJson'])->name('index');
        Route::post('/pin', [AssignmentPublicController::class, 'verifyPin'])
            ->middleware('throttle:10,1')->name('verify-pin');
        Route::post('/ganti-kelas', [AssignmentPublicController::class, 'clearPin'])->name('clear-pin');

        Route::get('/{assignment}', [AssignmentPublicController::class, 'showJson'])->name('show');
        Route::post('/{assignment}/submit', [AssignmentPublicController::class, 'submit'])
            ->middleware('throttle:10,1')->name('submit');
        Route::get('/{assignment}/download-attachment', [AssignmentPublicController::class, 'downloadAttachment'])
            ->name('download-attachment');
        Route::get('/{assignment}/submission/{submission}/download', [AssignmentPublicController::class, 'downloadOwnSubmission'])
            ->name('submission.download-own');
    });

    // ══════════════════════════════════════════════════════════════
    // GURU — hasil akhir: /api/tugas-admin/..., guard: token guru
    // (query/session) atau auth() admin/teknisi biasa
    // ══════════════════════════════════════════════════════════════

    Route::prefix('tugas-admin')->name('api.assignment-admin.')->group(function () {
        Route::get('/', [AssignmentAdminController::class, 'index'])->name('index');
        Route::get('/meta', [AssignmentAdminController::class, 'meta'])->name('meta');
        Route::post('/', [AssignmentAdminController::class, 'store'])->name('store');
        Route::get('/{assignment}', [AssignmentAdminController::class, 'show'])->name('show');
        Route::put('/{assignment}', [AssignmentAdminController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [AssignmentAdminController::class, 'destroy'])->name('destroy');

        Route::post('/{previous}/continuation', [AssignmentAdminController::class, 'storeContinuation'])->name('continuation');
        Route::post('/{assignment}/reopen', [AssignmentAdminController::class, 'reopen'])->name('reopen');

        Route::patch('/{assignment}/toggle-access', [AssignmentAdminController::class, 'toggleAccess'])->name('toggle-access');
        Route::patch('/{assignment}/toggle-student-download', [AssignmentAdminController::class, 'toggleStudentDownload'])
            ->name('toggle-student-download');

        Route::get('/{assignment}/download-attachment', [AssignmentAdminController::class, 'downloadAttachment'])
            ->name('download-attachment');
        Route::get('/{assignment}/download-zip', [AssignmentAdminController::class, 'downloadZip'])->name('download-zip');
        Route::get('/{assignment}/export-excel', [AssignmentAdminController::class, 'exportExcel'])->name('export-excel');

        Route::post('/submission/{submission}/grade', [AssignmentAdminController::class, 'gradeSubmission'])->name('submission.grade');
        Route::delete('/submission/{submission}', [AssignmentAdminController::class, 'destroySubmission'])->name('submission.destroy');
        Route::get('/submission/{submission}/download', [AssignmentAdminController::class, 'downloadSubmission'])
            ->name('submission.download');
    });

});
