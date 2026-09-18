<x-app-layout>
<x-slot name="title">Detail Booking</x-slot>

<div style="max-width:640px">
    <a href="{{ route('booking.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;margin-bottom:18px"
       onmouseover="this.style.color='#1A2517'" onmouseout="this.style.color='#6b7280'">
        ← Kembali ke Daftar Booking
    </a>

    <div style="background:#fff;border-radius:16px;border:1px solid #e8f0e6;overflow:hidden;box-shadow:0 1px 6px rgba(26,37,23,.06)">

        {{-- Header --}}
        <div style="padding:20px 24px;background:linear-gradient(135deg,#1A2517,#2d3d29);display:flex;align-items:flex-start;justify-content:space-between">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(172,200,162,.6);margin-bottom:4px">Detail Booking</p>
                <h2 style="font-family:Outfit,sans-serif;font-weight:700;font-size:19px;color:#fff;margin:0">{{ $booking->title }}</h2>
            </div>
            @if($booking->status === 'pending')
                <span style="background:rgba(253,230,138,.15);color:#fcd34d;border:1px solid rgba(253,230,138,.3);font-size:12px;font-weight:700;padding:5px 12px;border-radius:999px">⏳ Pending</span>
            @elseif($booking->status === 'approved')
                <span style="background:rgba(134,239,172,.12);color:#86efac;border:1px solid rgba(134,239,172,.25);font-size:12px;font-weight:700;padding:5px 12px;border-radius:999px">✓ Disetujui</span>
            @else
                <span style="background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.25);font-size:12px;font-weight:700;padding:5px 12px;border-radius:999px">✗ Ditolak</span>
            @endif
        </div>

        {{-- Info grid --}}
        <div style="padding:20px 24px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
            @foreach([
                ['label'=>'Lab','value'=>$booking->resource->name??'-'],
                ['label'=>'Tanggal','value'=>\Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y')],
                ['label'=>'Slot Waktu','value'=>$booking->timeSlot ? $booking->timeSlot->name.' · '.\Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i').'–'.\Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') : '-'],
                ['label'=>'Nama Pengajar','value'=>$booking->teacher_name],
                ['label'=>'No HP','value'=>$booking->teacher_phone],
                ['label'=>'Kelas','value'=>$booking->class_name??'-'],
                ['label'=>'Mata Pelajaran','value'=>$booking->subject_name??'-'],
                ['label'=>'Jumlah Peserta','value'=>$booking->participant_count.' orang'],
            ] as $row)
            <div style="border-bottom:1px solid #f5f5f5;padding-bottom:12px">
                <p style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">{{ $row['label'] }}</p>
                <p style="font-size:14px;font-weight:600;color:#1A2517">{{ $row['value'] }}</p>
            </div>
            @endforeach

            @if($booking->description)
            <div style="grid-column:1/-1;border-bottom:1px solid #f5f5f5;padding-bottom:12px">
                <p style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">Keterangan</p>
                <p style="font-size:14px;color:#374151">{{ $booking->description }}</p>
            </div>
            @endif

            @if($booking->notes)
            <div style="grid-column:1/-1">
                <p style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">
                    {{ $booking->status === 'rejected' ? 'Alasan Penolakan' : 'Catatan Admin' }}
                </p>
                <p style="font-size:14px;color:#374151;padding:10px 14px;background:{{ $booking->status==='rejected'?'#fef2f2':'#f0fdf4' }};border-radius:8px;border-left:3px solid {{ $booking->status==='rejected'?'#f87171':'#86efac' }}">
                    {{ $booking->notes }}
                </p>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        @if($booking->status === 'pending')
        <div style="padding:16px 24px;border-top:1px solid #f0f4ee;display:flex;gap:10px">
            <form method="POST" action="{{ route('booking.approve', $booking->id) }}" onsubmit="return confirm('Setujui booking ini?')" style="flex:1">
                @csrf @method('PATCH')
                <button type="submit" style="width:100%;background:linear-gradient(135deg,#1A2517,#2d3d29);color:#ACC8A2;border:none;border-radius:10px;padding:12px;font-size:14px;font-weight:700;cursor:pointer">
                    ✓ Setujui Booking
                </button>
            </form>
            <button onclick="document.getElementById('reject-section').style.display='block';this.style.display='none'"
                style="flex:1;background:#fff;color:#dc2626;border:1.5px solid #fecaca;border-radius:10px;padding:12px;font-size:14px;font-weight:700;cursor:pointer">
                ✗ Tolak
            </button>
        </div>
        <div id="reject-section" style="display:none;padding:0 24px 20px">
            <form method="POST" action="{{ route('booking.reject', $booking->id) }}">
                @csrf @method('PATCH')
                <textarea name="notes" rows="3" required placeholder="Alasan penolakan..."
                    style="width:100%;border:1.5px solid #fecaca;border-radius:10px;padding:10px 13px;font-size:13px;font-family:DM Sans,sans-serif;outline:none;resize:none;box-sizing:border-box;color:#374151"
                    onfocus="this.style.borderColor='#f87171'" onblur="this.style.borderColor='#fecaca'"></textarea>
                <button type="submit"
                    style="margin-top:8px;width:100%;background:#dc2626;color:#fff;border:none;border-radius:10px;padding:11px;font-size:14px;font-weight:700;cursor:pointer">
                    Konfirmasi Penolakan
                </button>
            </form>
        </div>
        @endif

        <div style="padding:12px 24px;border-top:1px solid #f0f4ee">
            <p style="font-size:11px;color:#9ca3af">Diajukan: {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y, H:i') }}</p>
        </div>
    </div>
</div>

</x-app-layout>