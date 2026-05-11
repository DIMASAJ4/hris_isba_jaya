@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="py-4">
  <div class="mb-6">
    <h2 class="text-xl font-bold text-navy">Statistik Keanggotaan</h2>
    <p class="text-gray-500 text-sm mt-1">
      Selamat datang kembali, {{ auth()->user()->name }}.
      Berikut ringkasan data organisasi hari ini.
    </p>
  </div>

  {{-- Stat Cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @php
    $cards = [
      ['label'=>'Total Anggota',   'value'=>$stats['total_members'],    'icon'=>'users',   'color'=>'blue',  'badge'=>'+2 bulan ini',  'badge_color'=>'green'],
      ['label'=>'Anggota Aktif',   'value'=>$stats['active_members'],   'icon'=>'check',   'color'=>'green', 'badge'=>'AKTIF',         'badge_color'=>'green'],
      ['label'=>'Tidak Aktif',     'value'=>$stats['inactive_members'], 'icon'=>'x',       'color'=>'red',   'badge'=>'TIDAK AKTIF',   'badge_color'=>'red'],
      ['label'=>'Departemen',      'value'=>$stats['total_departments'],'icon'=>'building','color'=>'navy',  'badge'=>'AKTIF',         'badge_color'=>'blue'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="card p-5">
      <div class="flex items-start justify-between mb-4">
        <div class="w-10 h-10 rounded-lg bg-{{ $card['color'] === 'navy' ? 'navy' : $card['color'] }}-100
          flex items-center justify-center">
          <svg class="w-5 h-5 text-{{ $card['color'] === 'navy' ? 'primary' : $card['color'] }}-600"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($card['icon']==='users')
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            @elseif($card['icon']==='check')
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @elseif($card['icon']==='x')
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @else
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            @endif
          </svg>
        </div>
        <span class="badge-{{ strtolower(str_replace(' ','-',$card['badge_color'] === 'green' ? 'aktif' : ($card['badge_color'] === 'red' ? 'tidak-aktif' : 'alumni'))) }}
          text-xs">{{ $card['badge'] }}</span>
      </div>
      <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $card['label'] }}</p>
      <p class="text-3xl font-bold text-navy">{{ $card['value'] }}</p>
    </div>
    @endforeach
  </div>

  {{-- Charts + Table area --}}
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5">
    <div class="card col-span-3 p-5">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-navy">Anggota per Departemen</h3>
      </div>
      <div class="h-48 flex items-end justify-around gap-4">
        @foreach($membersByDept as $dept)
        <div class="flex flex-col items-center gap-2 flex-1">
          <span class="text-xs font-semibold text-gray-600">{{ $dept['count'] }}</span>
          <div class="w-full rounded-t-md transition-all"
            style="height: {{ $dept['count'] > 0 ? ($dept['count'] / max(collect($membersByDept)->pluck('count')->toArray() ?: [1])) * 140 : 4 }}px;
                   background-color: {{ $dept['color'] }}">
          </div>
          <span class="text-xs text-gray-500 text-center leading-tight">{{ $dept['name'] }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <div class="card col-span-2 p-5">
      <h3 class="font-semibold text-navy mb-4">Status Keanggotaan</h3>
      <div class="space-y-3">
        @php
        $statusColors = ['Aktif'=>'bg-green-500','Tidak Aktif'=>'bg-red-500','Alumni'=>'bg-blue-500','Pending'=>'bg-amber-500'];
        $total = $statusData->sum();
        @endphp
        @foreach($statusData as $status => $count)
        <div>
          <div class="flex justify-between text-sm mb-1">
            <span class="text-gray-600">{{ $status }}</span>
            <span class="font-semibold text-navy">{{ $count }}</span>
          </div>
          <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="{{ $statusColors[$status] ?? 'bg-gray-400' }} h-full rounded-full"
              style="width: {{ $total > 0 ? round(($count/$total)*100) : 0 }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Latest Members Table --}}
  <div class="card">
    <div class="card-header">
      <h3 class="font-semibold text-navy">Anggota Terbaru Ditambahkan</h3>
      @role('admin')
      <a href="{{ route('admin.members.index') }}" class="text-sm text-primary hover:underline">
        Lihat Semua →
      </a>
      @endrole
    </div>
    <div class="table-wrapper rounded-none rounded-b-xl">
      <table class="table">
        <thead>
          <tr>
            <th>Nama</th><th>NIM</th><th>Departemen</th>
            <th>Jabatan</th><th>Status</th><th>Tgl Bergabung</th>
          </tr>
        </thead>
        <tbody>
          @forelse($latestMembers as $member)
          <tr>
            <td>
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center
                  text-primary text-xs font-bold flex-shrink-0">
                  {{ strtoupper(substr($member->full_name, 0, 1)) }}
                </div>
                <span class="font-medium text-gray-800">{{ $member->full_name }}</span>
              </div>
            </td>
            <td class="font-mono text-gray-500 text-xs">{{ $member->nim }}</td>
            <td>{{ $member->department->name ?? '-' }}</td>
            <td>{{ $member->position->name ?? '-' }}</td>
            <td>
              <span class="badge-{{ strtolower(str_replace(' ','-',$member->status)) }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                {{ $member->status }}
              </span>
            </td>
            <td class="text-gray-500">
              {{ $member->joined_at ? $member->joined_at->format('d M Y') : '-' }}
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-gray-400 py-8">Belum ada data anggota</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
