<x-app-layout>
<x-slot name="title">Data Guru</x-slot>

<style>
.tg-wrap { max-width: 960px; margin: 0 auto; padding: 28px 20px; }
.tg-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
.tg-title { font-size: 22px; font-weight: 800; color: #0d2416; font-family: Outfit, sans-serif; }
.tg-sub { font-size: 13px; color: #6b8fa3; margin-top: 2px; }
.tg-btn { background: #1a5c38; color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; }
.tg-btn:hover { background: #145230; }

/* Table */
.tg-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
.tg-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.tg-table thead th { background: #f8faf9; padding: 11px 14px; text-align: left; font-weight: 600; color: #4a6572; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid #e8f0ec; }
.tg-table tbody tr { border-bottom: 1px solid #f0f5f2; }
.tg-table tbody tr:last-child { border-bottom: none; }
.tg-table tbody tr:hover { background: #f8faf9; }
.tg-table td { padding: 12px 14px; color: #1a2e25; vertical-align: middle; }
.tg-token { font-family: 'JetBrains Mono', monospace; background: #e8f4fb; color: #1a5c38; padding: 2px 8px; border-radius: 5px; font-size: 12px; font-weight: 600; }
.tg-badge-on  { background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.tg-badge-off { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.tg-count { background: #f0f5f2; color: #1a5c38; padding: 2px 8px; border-radius: 5px; font-size: 12px; font-weight: 600; }
.tg-actions { display: flex; gap: 6px; align-items: center; }
.tg-act-btn { border: none; background: transparent; cursor: pointer; padding: 5px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; }
.tg-act-edit { color: #1a5c38; background: #e8f4fb; }
.tg-act-edit:hover { background: #d0ebd6; }
.tg-act-del  { color: #dc2626; background: #fee2e2; }
.tg-act-del:hover { background: #fecaca; }
.tg-empty { text-align: center; padding: 48px 20px; color: #6b8fa3; font-size: 14px; }

/* Modal */
.tg-modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 9999; align-items: center; justify-content: center; }
.tg-modal-bg.open { display: flex; }
.tg-modal { background: #fff; border-radius: 14px; padding: 28px; width: 100%; max-width: 420px; box-shadow: 0 8px 32px rgba(0,0,0,.18); }
.tg-modal-title { font-size: 17px; font-weight: 800; color: #0d2416; margin-bottom: 18px; font-family: Outfit, sans-serif; }
.tg-field { margin-bottom: 14px; }
.tg-label { display: block; font-size: 12px; font-weight: 600; color: #4a6572; margin-bottom: 5px; }
.tg-input { width: 100%; border: 1.5px solid #d1e5d8; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #1a2e25; box-sizing: border-box; }
.tg-input:focus { outline: none; border-color: #1a5c38; }
.tg-modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
.tg-cancel-btn { background: #f0f5f2; color: #4a6572; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
.tg-save-btn { background: #1a5c38; color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
</style>

<div class="tg-wrap">

    {{-- Flash --}}
    @if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:11px 16px;border-radius:8px;margin-bottom:18px;font-size:13px;font-weight:600">
        ✓ {{ session('success') }}
    </div>
    @endif

    <div class="tg-header">
        <div>
            <div class="tg-title">Data Guru</div>
            <div class="tg-sub">{{ $teachers->count() }} guru terdaftar</div>
        </div>
        <button class="tg-btn" onclick="openAddModal()">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Guru
        </button>
    </div>

    <div class="tg-card">
        <table class="tg-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Jadwal</th>
                    <th>Booking</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $i => $teacher)
                <tr>
                    <td style="color:#9aadaa;font-size:12px">{{ $i + 1 }}</td>
                    <td style="font-weight:600">{{ $teacher->name }}</td>
                    <td>{{ $teacher->phone ?? '-' }}</td>
                    <td><span class="tg-count">{{ $teacher->schedules_count }}</span></td>
                    <td><span class="tg-count">{{ $teacher->bookings_count }}</span></td>
                    <td>
                        @if($teacher->is_active)
                            <span class="tg-badge-on">Aktif</span>
                        @else
                            <span class="tg-badge-off">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="tg-actions">
                            <button class="tg-act-btn tg-act-edit"
                                onclick="openEditModal({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', '{{ $teacher->phone }}', {{ $teacher->is_active ? 1 : 0 }})">
                                Edit
                            </button>
                            <form action="{{ route('teacher.destroy', $teacher) }}" method="POST"
                                onsubmit="return confirm('Nonaktifkan guru {{ addslashes($teacher->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="tg-act-btn tg-act-del">Nonaktifkan</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="tg-empty">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="tg-modal-bg" id="modal-add">
    <div class="tg-modal">
        <div class="tg-modal-title">Tambah Guru</div>
        <form action="{{ route('teacher.store') }}" method="POST">
            @csrf
            <div class="tg-field">
                <label class="tg-label">Nama Lengkap *</label>
                <input type="text" name="name" class="tg-input" required placeholder="Contoh: Budi Santoso, S.Kom">
            </div>
            <div class="tg-field">
                <label class="tg-label">No. HP (opsional)</label>
                <input type="text" name="phone" class="tg-input" placeholder="08xxxxxxxxxx">
            </div>
            <div class="tg-modal-footer">
                <button type="button" class="tg-cancel-btn" onclick="closeModal('modal-add')">Batal</button>
                <button type="submit" class="tg-save-btn">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="tg-modal-bg" id="modal-edit">
    <div class="tg-modal">
        <div class="tg-modal-title">Edit Guru</div>
        <form id="form-edit" action="" method="POST">
            @csrf @method('PATCH')
            <div class="tg-field">
                <label class="tg-label">Nama Lengkap *</label>
                <input type="text" id="edit-name" name="name" class="tg-input" required>
            </div>
            <div class="tg-field">
                <label class="tg-label">No. HP</label>
                <input type="text" id="edit-phone" name="phone" class="tg-input">
            </div>
            <div class="tg-field">
                <label class="tg-label">Status</label>
                <select id="edit-active" name="is_active" class="tg-input">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="tg-modal-footer">
                <button type="button" class="tg-cancel-btn" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="tg-save-btn">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modal-add').classList.add('open');
}
function openEditModal(id, name, phone, isActive) {
    const base = '{{ url('/data-guru') }}';
    document.getElementById('form-edit').action = base + '/' + id;
    document.getElementById('edit-name').value    = name;
    document.getElementById('edit-phone').value   = phone || '';
    document.getElementById('edit-active').value  = isActive;
    document.getElementById('modal-edit').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
// Tutup modal kalau klik backdrop
document.querySelectorAll('.tg-modal-bg').forEach(bg => {
    bg.addEventListener('click', e => { if (e.target === bg) bg.classList.remove('open'); });
});
</script>

</x-app-layout>
