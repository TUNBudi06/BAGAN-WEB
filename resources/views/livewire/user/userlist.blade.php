<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\UserBaganList;

new class extends Component {
    use WithFileUploads;

    public $modalOpen = true;

    #[Validate(['required', 'string', 'max:255'])]
    public string $nama = "";

    #[Validate(['required', 'string', 'max:255'])]
    public string $team = "";

    #[Validate(['required', 'string', 'max:50', 'unique:user_bagan_lists,nik'])]
    public string $nik = "";

    #[Validate(['nullable', 'image', 'max:2048','extensions:jpg,jpeg,png,gif'])]
    public $image;

    public function mount(): void
    {
        $this->modalOpen = false;
    }

    public function closeModal(): void
    {
        $this->modalOpen = false;
        $this->reset(['nama', 'team', 'nik', 'image']);
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('user-images', 'public');
        }

        UserBaganList::create([
            'nama' => $this->nama,
            'team' => $this->team,
            'nik' => $this->nik,
            'image_path' => $imagePath,
        ]);

        $this->dispatch('Notify', [
            'type' => 'success',
            'message' => 'User bagan berhasil ditambahkan.',
            'title' => 'Sukses',
        ]);
        $this->dispatch('RefreshUserBaganList');
        $this->closeModal();
    }
}; ?>

<div>
    <div x-data="{ modalOpen: @entangle('modalOpen') }"
         @keydown.escape.window="$wire.closeModal()"
         :class="{ 'z-40': modalOpen }" class="relative w-auto h-auto">
        <button @click="modalOpen=true"
                class="flex justify-start items-center px-4 py-2 h-10 text-sm font-medium bg-blue-600 text-white rounded-md border transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Member
        </button>
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen"
                 x-cloak>
                <div x-show="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     wire:click="closeModal"
                     class="absolute inset-0 w-full h-full backdrop-blur-sm bg-gray-900/50"></div>
                <div x-show="modalOpen"
                     x-trap.inert.noscroll="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     class="relative px-7 py-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-md drop-shadow-md backdrop-blur-sm bg-white/95 sm:rounded-lg">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <h3 class="text-xl font-semibold text-gray-900">Tambah Member Bagan</h3>
                        <button wire:click="closeModal"
                                class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-400 rounded-full hover:text-gray-600 hover:bg-gray-100">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="mt-6 space-y-6">
                        <!-- Nama Field -->
                        <div>
                            <label for="nama" class="flex justify-start text-sm font-medium text-gray-700 mb-2">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama" wire:model="nama"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Masukkan nama lengkap"/>
                            @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- NIK Field -->
                        <div>
                            <label for="nik" class="flex justify-start text-sm font-medium text-gray-700 mb-2">
                                NIK (Nomor Induk Kerja) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nik" wire:model="nik"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Contoh: 123456"/>
                            @error('nik') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Team Field -->
                        <div>
                            <label for="team" class="flex justify-start text-sm font-medium text-gray-700 mb-2">
                                Team <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="team" wire:model="team"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Contoh: IT, Marketing, Finance"/>
                            @error('team') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Image Upload Field -->
                        <div>
                            <label for="image" class="flex justify-start text-sm font-medium text-gray-700 mb-2">
                                Foto Profil
                            </label>
                            <input type="file" id="image" wire:model.defer="image" accept="image/*"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   onchange="previewImage(this)"/>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            <!-- Livewire Preview (shows after blur/submit) -->
                            @if ($image)
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Server Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-300 shadow-sm">
                                    <p class="text-xs text-green-600 mt-1">✓ File uploaded to server</p>
                                </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 pt-6 border-t">
                            <button wire:click="closeModal" type="button"
                                    class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Batal
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>
