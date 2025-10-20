<?php

use App\Models\BaganList;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public $baganList = null;

    public function mount(){
        $this->GetBaganList();
    }

    public function GetBaganList()
    {
        $this->baganList = BaganList::all();
    }

    #[On('refreshBaganList')]
    public function refreshBaganList()
    {
        $this->GetBaganList();
    }

    public function deleteBagan($baganId)
    {
        try {
            $bagan = BaganList::find($baganId);

            if (!$bagan) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Bagan tidak ditemukan!',
                    'title' => 'Error'
                ]);
                return;
            }

            $bagan->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Bagan berhasil dihapus!',
                'title' => 'Berhasil'
            ]);

            // Refresh the list
            $this->GetBaganList();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus bagan: ' . $e->getMessage(),
                'title' => 'Error'
            ]);
        }
    }

    public function with()
    {
        return [
            'baganList' => $this->baganList,
        ];
    }
}; ?>

<div class="mt-4 bg-white rounded-xl w-full pt-4">
    <div class="mb-4 px-6">
        <h3 class="text-xl font-bold">List Bagan</h3>
        <p class="text-sm text-gray-500">Semua list yang ada di Web Bagan ini</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bagan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($baganList as $bagan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bagan->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $bagan->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bagan->created_at ? $bagan->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{route('bagan-edit',['id' => $bagan->id])}}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Bagan
                                </a>
                                <button wire:click="deleteBagan({{ $bagan->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150"
                                        onclick="confirm('Anda yakin ingin menghapus bagan ini?') || event.stopImmediatePropagation()">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada bagan yang dibuat
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
