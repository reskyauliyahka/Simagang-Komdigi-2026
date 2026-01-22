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
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dikirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $s)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $s->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $s->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->format('d M Y H:i') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset('storage/'.$s->photo_path) }}" class="w-12 h-12 object-cover rounded border cursor-pointer" onclick="window.open('{{ asset('storage/'.$s->photo_path) }}','_blank')">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('intern.microskill.edit', $s->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('intern.microskill.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mikro skill ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada pengumpulan.</td>
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