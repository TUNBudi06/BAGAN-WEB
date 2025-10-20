<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use App\Models\BaganList;

new class extends Component {

    #[Validate(['required', 'string', 'min:3', 'max:255'])]
    public string $name = '';

    public function save()
    {
        $this->validate();

        try {
            BaganList::create([
                'name' => $this->name,
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Bagan berhasil ditambahkan!',
                'title' => 'Berhasil'
            ]);

            // Reset form after successful save
            $this->reset('name');

            // Dispatch event to refresh any bagan list components
            $this->dispatch('refreshBaganList');

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage(),
                'title' => 'Error'
            ]);
        }
    }
}; ?>

<div class="bg-white rounded-xl w-full p-3">
    <form wire:submit="save">
        <div class="flex w-full justify-items-start">
            <div class="flex-1 pe-3">
                <input type="text"
                       wire:model="name"
                       placeholder="Masukkan nama bagan..."
                       class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 disabled:cursor-not-allowed disabled:opacity-50 @error('name') border-red-500 focus:border-red-500 focus:ring-red-400 @enderror" />
            </div>
            <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 bg-blue-600 border border-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Tambahkan Bagan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </form>
</div>
