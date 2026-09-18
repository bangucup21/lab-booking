<x-app-layout>
<x-slot name="title">Kelola Lab</x-slot>

<style>
.lb-wrap  { max-width: 1000px; margin: 0 auto; padding: 28px 20px; }
.lb-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.lb-title  { font-size: 22px; font-weight: 800; color: #0d2416; font-family: Outfit, sans-serif; }
.lb-sub    { font-size: 13px; color: #6b8fa3; margin-top: 2px; }
.lb-btn    { background: #1a5c38; color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; }
.lb-btn:hover { background: #145230; }

/* Cards grid */
.lb-grid   { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
.lb-card   { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); padding: 20px; display: flex; flex-direction: column; gap: 10px; }
.lb-card-name  { font-size: 16px; font-weight: 700; color: #0d2416; font-family: Outfit, sans-serif; }
.lb-card-meta  { font-size: 12px; color: #6b8fa3; display: flex; flex-direction: column; gap: 3px; }
.lb-card-meta span { display: flex; align-items: center; gap: 5px; }
.lb-badge-active { background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.lb-badge-inactive { background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.lb-badge-maintenance { background: #fef9c3; color: #854d0e; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.lb-card-counts { display: flex; gap: 10px; }
.lb-count { background: #f0f5f2; color: #1a5c38; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
.lb-card-actions { display: flex; gap: 8px; margin-top: 4px; }
.lb-act  { border: none; padding: 6px 14px; border-radius: 7px; font-size: 12px; font-weight: 600; cursor: pointer; }
.lb-act-edit { background: #e8f4fb; color: #1a5c38; }
.lb-act-edit:hover { background: #d0ebd6; }
.lb-act-del  { background: #fee2e2; color: #dc2626; }
.lb-act-del:hover { background: #fecaca; }

.lb-empty { text-align: center; padding: 60px 20px; color: #6b8fa3; font-size: 14px; background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); }

/* Modal */
.lb-modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 9999; align-items: center; justify-content: center; }
.lb-modal-bg.open { display: flex; }
.lb-modal  { background: #fff; border-radius: 14px; padding: 28px; width: 100%; max-width: 460px; box-shadow: 0 8px 32px rgba(0,0,0,.18); max-height: 90vh; overflow-y: auto; }
.lb-modal-title { font-size: 17px; font-weight: 800; color: #0d2416; margin-bottom: 18px; font-family: Outfit, sans-serif; }
.lb-row2  { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.lb-field { margin-bottom: 14px; }
.lb-label { display: block; font-size: 12px; font-weight: 600; color: #4a6572; margin-bottom: 5px; }
.lb-input { width: 100%; border: 1.5px solid #d1e5d8; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #1a2e25; box-sizing: border-box; }
.lb-input:focus { outline: none; border-color: #1a5c38; }
.lb-modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
.lb-cancel { background: #f0f5f2; color: #4a6572; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
.lb-save   { background: #1a5c38; color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
</style>

<div class="lb-wrap">

    {{-- Flash --}}
    @if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:11px 16px;border-radius:8px;margin-bottom:18px;font-size:13px;font-weight:600">
        ✓ {{ session('success') }}
    </div>
    @endif

    <div class="lb-header">
        <div>
            <div class="lb-title">Kelola Lab</div>
            <div class="lb-sub">{{ $labs->count() }} laboratorium terdaftar</div>
        </div>
        <button class="lb-btn" onclick="openAddModal()">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Lab
        </button>
    </div>

    @if($labs->isEmpty())
    <div class="lb-empty">Belum ada laboratorium. Klik "Tambah Lab" untuk memulai.</div>
    @else
    <div class="lb-grid">
        @foreach($labs as $lab)
        <div class="lb-card">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                <div class="lb-card-name">{{ $lab->name }}</div>
                @if($lab->status === 'active')
                    <span class="lb-badge-active">Aktif</span>
                @elseif($lab->status === 'maintenance')
                    <span class="lb-badge-maintenance">Maintenance</span>
                @else
                    <span class="lb-badge-inactive">Nonaktif</span>
                @endif
            </div>

            <div class="lb-card-meta">
                @if($lab->building || $lab->room_number)
                <span>
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ implode(', ', array_filter([$lab->building, $lab->floor ? 'Lantai '.$lab->floor : null, $lab->room_number ? 'Ruang '.$lab->room_number : null])) }}
                </span>
                @endif
                @if($lab->capacity)
                <span>
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Kapasitas {{ $lab->capacity }} komputer
                </span>
                @endif
                @if($lab->organization)
                <span>
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    {{ $lab->organization->name }}
                </span>
                @endif
            </div>

            <div class="lb-card-counts">
                <span class="lb-count">{{ $lab->schedules_count }} jadwal</span>
                <span class="lb-count">{{ $lab->bookings_count }} booking</span>
            </div>

            <div class="lb-card-actions">
                <button class="lb-act lb-act-edit" onclick="openEditModal(
                    {{ $lab->id }},
                    '{{ addslashes($lab->name) }}',
                    '{{ $lab->type }}',
                    '{{ $lab->organization_id }}',
                    '{{ addslashes($lab->building ?? '') }}',
                    '{{ $lab->floor ?? '' }}',
                    '{{ addslashes($lab->room_number ?? '') }}',
                    '{{ $lab->capacity ?? '' }}',
                    '{{ $lab->status }}'
                )">Edit</button>

                <form action="{{ route('lab.destroy', $lab) }}" method="POST"
                    onsubmit="return confirm('Hapus lab {{ addslashes($lab->name) }}? Data jadwal dan booking terkait juga akan terpengaruh.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="lb-act lb-act-del">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div class="lb-modal-bg" id="modal-add">
    <div class="lb-modal">
        <div class="lb-modal-title">Tambah Lab</div>
        <form action="{{ route('lab.store') }}" method="POST">
            @csrf
            <div class="lb-field">
                <label class="lb-label">Nama Lab *</label>
                <input type="text" name="name" class="lb-input" required placeholder="Contoh: Lab Komputer 7">
            </div>
            <div class="lb-row2">
                <div class="lb-field">
                    <label class="lb-label">Tipe *</label>
                    <select name="type" class="lb-input" required>
                        <option value="lab">Lab Komputer</option>
                        <option value="multimedia">Multimedia</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="lb-field">
                    <label class="lb-label">Kapasitas</label>
                    <input type="number" name="capacity" class="lb-input" min="1" placeholder="36">
                </div>
            </div>
            <div class="lb-row2">
                <div class="lb-field">
                    <label class="lb-label">Gedung</label>
                    <input type="text" name="building" class="lb-input" placeholder="Gedung A">
                </div>
                <div class="lb-field">
                    <label class="lb-label">Lantai</label>
                    <input type="number" name="floor" class="lb-input" min="1" placeholder="2">
                </div>
            </div>
            <div class="lb-row2">
                <div class="lb-field">
                    <label class="lb-label">No. Ruang</label>
                    <input type="text" name="room_number" class="lb-input" placeholder="207">
                </div>
                <div class="lb-field">
                    <label class="lb-label">Organisasi</label>
                    <select name="organization_id" class="lb-input">
                        <option value="">— Pilih —</option>
                        @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="lb-modal-footer">
                <button type="button" class="lb-cancel" onclick="closeModal('modal-add')">Batal</button>
                <button type="submit" class="lb-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="lb-modal-bg" id="modal-edit">
    <div class="lb-modal">
        <div class="lb-modal-title">Edit Lab</div>
        <form id="form-edit" action="" method="POST">
            @csrf @method('PATCH')
            <div class="lb-field">
                <label class="lb-label">Nama Lab *</label>
                <input type="text" id="e-name" name="name" class="lb-input" required>
            </div>
            <div class="lb-row2">
                <div class="lb-field">
                    <label class="lb-label">Tipe *</label>
                    <select id="e-type" name="type" class="lb-input" required>
                        <option value="lab">Lab Komputer</option>
                        <option value="multimedia">Multimedia</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="lb-field">
                    <label class="lb-label">Kapasitas</label>
                    <input type="number" id="e-capacity" name="capacity" class="lb-input" min="1">
                </div>
            </div>
            <div class="lb-row2">
                <div class="lb-field">
                    <label class="lb-label">Gedung</label>
                    <input type="text" id="e-building" name="building" class="lb-input">
                </div>
                <div class="lb-field">
                    <label class="lb-label">Lantai</label>
                    <input type="number" id="e-floor" name="floor" class="lb-input" min="1">
                </div>
            </div>
            <div class="lb-row2">
                <div class="lb-field">
                    <label class="lb-label">No. Ruang</label>
                    <input type="text" id="e-room" name="room_number" class="lb-input">
                </div>
                <div class="lb-field">
                    <label class="lb-label">Organisasi</label>
                    <select id="e-org" name="organization_id" class="lb-input">
                        <option value="">— Pilih —</option>
                        @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="lb-field">
                <label class="lb-label">Status</label>
                <select id="e-status" name="status" class="lb-input">
                    <option value="active">Aktif</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="lb-modal-footer">
                <button type="button" class="lb-cancel" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="lb-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modal-add').classList.add('open');
}
function openEditModal(id, name, type, orgId, building, floor, room, capacity, status) {
    document.getElementById('form-edit').action = '{{ url('/kelola-lab') }}/' + id;
    document.getElementById('e-name').value     = name;
    document.getElementById('e-capacity').value = capacity;
    document.getElementById('e-building').value = building;
    document.getElementById('e-floor').value    = floor;
    document.getElementById('e-room').value     = room;
    document.getElementById('e-type').value     = type;
    document.getElementById('e-org').value      = orgId || '';
    document.getElementById('e-status').value   = status;
    document.getElementById('modal-edit').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.lb-modal-bg').forEach(bg => {
    bg.addEventListener('click', e => { if (e.target === bg) bg.classList.remove('open'); });
});
</script>

</x-app-layout>
