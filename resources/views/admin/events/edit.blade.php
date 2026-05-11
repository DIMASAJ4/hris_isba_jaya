@extends('layouts.admin')
@section('title', 'Edit Berita Acara')
@section('page-title', 'Edit Berita Acara: ' . $event->event_code)

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="card p-8">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label">Judul / Nama Acara</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-input" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Tanggal Acara</label>
                        <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}" class="form-input" required>
                        @error('event_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Tempat / Lokasi</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" class="form-input" required>
                        @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Deskripsi / Agenda Acara</label>
                        <textarea name="description" rows="5" class="form-input" required>{{ old('description', $event->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $event->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Status Dokumen</label>
                        <select name="status" class="form-select" required>
                            <option value="Draft" {{ old('status', $event->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Selesai" {{ old('status', $event->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Dibatalkan" {{ old('status', $event->status) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.events.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-primary px-10">Perbarui Berita Acara</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
