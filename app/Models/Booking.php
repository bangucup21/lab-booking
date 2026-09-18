<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'session_id',           // ← TAMBAH: grupkan slot satu sesi
        'teacher_id',           // ← TAMBAH: relasi ke tabel teachers
        'resource_id',
        'time_slot_id',
        'user_id',
        'organization_id',
        'booking_date',
        'teacher_name',
        'teacher_phone',
        'class_name',
        'subject_name',
        'title',
        'description',
        'participant_count',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'approved_at'  => 'datetime',
    ];

    // ─── RELATIONS ────────────────────────────────────────────────────

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── SCOPES (yang sudah ada) ───────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // ─── SCOPES BARU ──────────────────────────────────────────────────

    /** Booking aktif (pending atau approved) */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    // ─── SESSION HELPERS ──────────────────────────────────────────────

    /**
     * Approve semua slot dalam satu sesi sekaligus
     * Dipakai di admin panel — satu klik approve semua slot
     */
    public static function approveSession(string $sessionId, int $approvedBy): int
    {
        return static::where('session_id', $sessionId)
            ->where('status', 'pending')
            ->update([
                'status'      => 'approved',
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);
    }

    /**
     * Reject semua slot dalam satu sesi sekaligus
     */
    public static function rejectSession(string $sessionId, ?string $notes = null): int
    {
        return static::where('session_id', $sessionId)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'notes'  => $notes,
            ]);
    }

    /**
     * Ambil semua booking dalam satu sesi, diurutkan by slot
     */
    public static function getSession(string $sessionId)
    {
        return static::with(['timeSlot', 'resource'])
            ->where('session_id', $sessionId)
            ->orderBy('time_slot_id')
            ->get();
    }
}