{{-- resources/views/schedule/partials/week-nav.blade.php --}}
@php
    $nextWeekStart = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addWeek();
    $isMaxWeek     = $weekStart->gte($nextWeekStart);
@endphp

<div class="week-nav">
    <button onclick="changeWeek('{{ $prevWeek }}')" class="week-btn week-btn-prev">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="hide-xs">Minggu Lalu</span>
    </button>

    <span class="week-label" id="week-label">
        📅
        <span class="hide-xs">{{ $weekStart->translatedFormat('d M Y') }} – {{ $weekEnd->translatedFormat('d M Y') }}</span>
        <span class="show-xs">{{ $weekStart->translatedFormat('d M') }} – {{ $weekEnd->translatedFormat('d M Y') }}</span>
    </span>

    <button onclick="changeWeek('{{ $nextWeek }}')"
        class="week-btn week-btn-next"
        {{ $isMaxWeek ? 'disabled' : '' }}
        style="{{ $isMaxWeek ? 'opacity:.4;cursor:not-allowed;pointer-events:none' : '' }}">
        <span class="hide-xs">Minggu Depan</span>
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</div>