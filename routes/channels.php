<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel per-kelas untuk dashboard siswa (React Student App /app/)
// Channel PUBLIC: siswa tidak perlu login — cukup PIN yang valid sudah jadi guard.
Broadcast::channel('class_assignments.{classSlug}', function () {
    return true;
});

// Channel per-tugas untuk panel guru + halaman publik detail
// Channel PUBLIC: guard-nya sudah dilakukan di controller masing-masing (token guru / PIN kelas).
Broadcast::channel('assignments.{assignmentId}', function () {
    return true;
});
