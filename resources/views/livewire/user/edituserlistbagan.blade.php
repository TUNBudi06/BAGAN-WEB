<?php

use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\UserBaganList;

new class extends Component {
    use WithFileUploads;

    public bool $modalOpen = false;
    public ?int $userId = null;

    #[Validate(['required', 'string', 'max:255'])]
    public string $nama = "";

    #[Validate(['required', 'string', 'max:255'])]
    public string $team = "";

    public string $nik = "";

    #[Validate(['nullable', 'image', 'max:2048','extensions:jpg,jpeg,png,gif'])]
    public $image;

    public ?string $currentImagePath = null;

    public function mount()
    {
        $this->modalOpen = false;
    }

    #[On("OpenUserEditModal")]
    public function OpenModal($userId)
    {
        debug($userId);
        $this->userId = $userId;
        $this->loadUserData();
        $this->modalOpen = true;
    }

    public function loadUserData()
    {
        if ($this->userId) {
            $user = UserBaganList::find($this->userId);
            if ($user) {
                $this->nama = $user->nama;
                $this->team = $user->team;
                $this->nik = $user->nik;
                $this->currentImagePath = $user->image_path;
            }
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['nama', 'team', 'nik', 'image', 'userId', 'currentImagePath']);
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'team' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:user_bagan_lists,nik,' . $this->userId,
        ]);

        if (!$this->userId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'User tidak ditemukan!',
                'title' => 'Error'
            ]);
            return;
        }

        $user = UserBaganList::find($this->userId);
        if (!$user) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'User tidak ditemukan!',
                'title' => 'Error'
            ]);
            return;
        }

        try {
            $imagePath = $this->currentImagePath;

            if ($this->image) {
                if ($this->currentImagePath) {
                    \Storage::disk('public')->delete($this->currentImagePath);
                }
                $imagePath = $this->image->store('user-images', 'public');
            }

            $user->update([
                'nama' => $this->nama,
                'team' => $this->team,
                'nik' => $this->nik,
                'image_path' => $imagePath,
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'User bagan berhasil diperbarui.',
                'title' => 'Sukses',
            ]);

            $this->closeModal();
            $this->dispatch('RefreshUserBaganList');
            $this->dispatch('reloadDataTable');

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui user: ' . $e->getMessage(),
                'title' => 'Error'
            ]);
        }
    }
}; ?>

<div>
    <div x-data="{ modalOpen: @entangle('modalOpen') }"
         @keydown.escape.window="$wire.closeModal()"
         :class="{ 'z-40': modalOpen }" class="relative w-auto h-auto">
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                <div x-show="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     wire:click="closeModal" class="absolute inset-0 w-full h-full backdrop-blur-sm bg-gray-900/50"></div>
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
                        <h3 class="text-xl font-semibold text-gray-900">Edit User Bagan</h3>
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

                            @if($currentImagePath && !$image)
                                <div class="mt-2 mb-3">
                                    <div class="relative inline-block">
                                        <img src="{{ Storage::url($currentImagePath) }}" alt="Current Image"
                                             class="w-32 h-32 object-cover rounded-lg border-2 border-blue-300 shadow-sm">
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded-b-lg">
                                            Foto Saat Ini
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="file" id="image" wire:model.defer="image" accept="image/*"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   onchange="previewEditImage(this)"/>
                            <p class="mt-1 text-xs text-gray-500">
                                Format: JPG, PNG, GIF. Maksimal 2MB.
                                @if($currentImagePath) Pilih file baru untuk mengganti foto saat ini. @endif
                            </p>
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            <div id="jsEditPreview" class="mt-2 hidden">
                                <div class="relative inline-block">
                                    <img id="previewEditImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-green-300 shadow-sm">
                                    <button type="button" onclick="clearEditImagePreview()"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold transition-colors shadow-md">
                                        ×
                                    </button>
                                    <div class="absolute bottom-0 left-0 right-0 bg-green-500 bg-opacity-90 text-white text-xs px-2 py-1 rounded-b-lg">
                                        Foto Baru
                                    </div>
                                </div>
                                <p class="text-xs text-green-600 mt-1">Foto baru yang akan digunakan. Click × untuk membatalkan.</p>
                            </div>

                            @if ($image)
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Server Preview"
                                         class="w-32 h-32 object-cover rounded-lg border-2 border-blue-300 shadow-sm">
                                    <p class="text-xs text-green-600 mt-1">✓ File berhasil diupload ke server</p>
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
                                <span wire:loading.remove>Perbarui</span>
                                <span wire:loading>Memperbarui...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function previewEditImage(input) {
    const jsPreview = document.getElementById('jsEditPreview');
    const previewImg = document.getElementById('previewEditImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            jsPreview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        jsPreview.classList.add('hidden');
    }
}

function clearEditImagePreview() {
    const jsPreview = document.getElementById('jsEditPreview');
    const previewImg = document.getElementById('previewEditImg');
    const fileInput = document.getElementById('image');

    previewImg.src = '';
    jsPreview.classList.add('hidden');
    fileInput.value = '';
}
</script>
