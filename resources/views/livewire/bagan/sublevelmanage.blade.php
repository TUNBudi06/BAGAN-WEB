<?php

use App\Models\SubLevels;
use Livewire\Volt\Component;

new class extends Component {
    public $subLevelList = null;
    public $idData = null;

    // Form properties
    public $name = '';
    public $value = '';
    public $showForm = false;

    public function mount($idData)
    {
        $this->idData = $idData;
        $this->GetSubLevelList();
    }

    public function GetSubLevelList()
    {
        // Assuming SubLevel is a model representing the sub levels
        $this->subLevelList = SubLevels::where('bagan_list_id', $this->idData)->get();
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->value = '';
    }

    public function addSubLevel()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        SubLevels::create([
            'bagan_list_id' => $this->idData,
            'name' => $this->name,
            'value' => $this->value,
        ]);

        $this->resetForm();
        $this->GetSubLevelList();
        $this->showForm = false;

        $this->dispatch('Notify', [
            'type' => 'success',
            'message' => 'Sub level berhasil ditambahkan!'
        ]);
    }

    public function deleteSubLevel($id)
    {
        SubLevels::find($id)->delete();
        $this->GetSubLevelList();
        $this->dispatch('Notify', [
            'type' => 'success',
            'message' => 'Sub level berhasil dihapus!'
        ]);
    }
}; ?>


<div>
    <!-- Header dengan tombol tambah -->
    <div class="flex justify-between items-center mb-3 sm:mb-4">
        <h3 class="font-bold text-lg sm:text-xl text-gray-900">Sub Level</h3>
        <button wire:click="toggleForm" class="inline-flex items-center px-3 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ $showForm ? 'Tutup Form' : 'Tambah Sub Level' }}
        </button>
    </div>

    <!-- Add Sub Level Form -->
    @if($showForm)
    <div class="mb-4 p-4 rounded-lg bg-gray-50 border">
        <h4 class="font-semibold text-gray-800 mb-3">Tambah Sub Level Baru</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sub Level</label>
                <input type="text" wire:model="name"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       placeholder="Masukkan nama sub level">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                <input type="text" wire:model="value"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       placeholder="Masukkan level">
                @error('value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button wire:click="addSubLevel"
                    class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Sub Level
            </button>
            <button wire:click="toggleForm"
                    class="inline-flex items-center px-4 py-2 rounded-md bg-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Batal
            </button>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="h-64 sm:h-72 lg:h-80 overflow-hidden rounded-lg border border-gray-200">
        <div class="h-full overflow-y-auto overscroll-contain">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-700">ID</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-700">Name</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-700">Level</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-center font-medium text-gray-700 w-24">Action</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subLevelList as $index => $subLevel)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-2 text-gray-900 font-medium">{{ $subLevel->id }}</td>
                            <td class="px-3 py-2 text-gray-700">
                                <div class="truncate">{{ $subLevel->name }}</div>
                            </td>
                            <td class="px-3 py-2 text-gray-700">{{ $subLevel->value }}</td>
                            <td class="px-3 py-2 text-center">
                                <button wire:click="deleteSubLevel({{ $subLevel->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus sub level ini?"
                                        class="inline-flex items-center px-2 py-1 rounded text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    <p class="text-sm">Belum ada sub level</p>
                                    @if(!$showForm)
                                        <button wire:click="toggleForm" class="mt-2 text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                            Tambah sub level pertama
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
