@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Mikro Skill Saya</h1>
        <a href="{{ route('intern.microskill.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload Bukti</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dikirim</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bukti</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $s)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $s->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $s->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->setTimezone('Asia/Makassar')->format('d M Y H:i') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset('storage/'.$s->photo_path) }}" class="w-12 h-12 object-cover rounded border cursor-pointer" onclick="window.open('{{ asset('storage/'.$s->photo_path) }}','_blank')">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('intern.microskill.edit', $s->id) }}" 
                                       class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all duration-200 group"
                                       title="Edit">
                                        <svg class="w-5 h-5 text-blue-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('intern.microskill.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mikro skill ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 rounded-lg transition-all duration-200 group"
                                                title="Hapus">
                                            <svg class="w-5 h-5 text-red-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pengumpulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $submissions->links() }}</div>
        </div>
    </div>
</div>
@endsection