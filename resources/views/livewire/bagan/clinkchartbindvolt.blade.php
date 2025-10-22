<?php

use Livewire\Volt\Component;
use App\Models\CLinkChartEmbed;
use Livewire\Attributes\Validate;

new class extends Component {
    public $bagan_id;
    public $clinks = [];

    #[Validate('optional')]
    public $label = '';

    #[Validate('required|integer')]
    public $from = '';

    #[Validate('required|integer')]
    public $to = '';

    #[Validate('required|in:blue,yellow,orange')]
    public $template = 'orange';

    public function mount($idData = null)
    {
        $this->bagan_id = $idData;
        $this->loadClinks();
    }

    public function loadClinks()
    {
        if ($this->bagan_id) {
            $this->clinks = CLinkChartEmbed::where('bagan_id', $this->bagan_id)->get()->toArray();
        }
    }

    public function addClink()
    {
        $this->validate();

        CLinkChartEmbed::create([
            'bagan_id' => $this->bagan_id,
            'label' => $this->label,
            'from' => $this->from,
            'to' => $this->to,
            'template' => $this->template,
        ]);

        $this->reset(['label', 'from', 'to', 'template']);
        $this->template = 'orange';
        $this->loadClinks();

        session()->flash('message', 'CLINK berhasil ditambahkan!');

        // Dispatch event to refresh chart
        $this->dispatch('refreshChart');
    }

    public function deleteClink($id)
    {
        CLinkChartEmbed::find($id)->delete();
        $this->loadClinks();

        session()->flash('message', 'CLINK berhasil dihapus!');

        // Dispatch event to refresh chart
        $this->dispatch('refreshChart');
    }
}; ?>

<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">CLINK Chart Management</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Form Add CLINK -->
        <div class="mb-8 bg-gray-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Tambah CLINK Baru</h3>
            <form wire:submit.prevent="addClink">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="label">
                            Label
                        </label>
                        <input wire:model="label" type="text" id="label"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('label') border-red-500 @enderror"
                            placeholder="Label">
                        @error('label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="from">
                            From
                        </label>
                        <input wire:model="from" type="number" id="from"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('from') border-red-500 @enderror"
                            placeholder="From">
                        @error('from') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="to">
                            To
                        </label>
                        <input wire:model="to" type="number" id="to"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('to') border-red-500 @enderror"
                            placeholder="To">
                        @error('to') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="template">
                            Template
                        </label>
                        <select wire:model="template" id="template"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('template') border-red-500 @enderror">
                            <option value="blue">Blue</option>
                            <option value="yellow">Yellow</option>
                            <option value="orange">Orange</option>
                        </select>
                        @error('template') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table CLINK -->
        <div class="overflow-x-auto">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Daftar CLINK</h3>
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Label</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">From</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">To</th>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Template</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clinks as $index => $clink)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b text-sm">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ $clink['label'] ?? '-' }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ $clink['from'] }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ $clink['to'] }}</td>
                            <td class="py-3 px-4 border-b text-sm">
                                <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold
                                    @if($clink['template'] == 'blue') bg-blue-500
                                    @elseif($clink['template'] == 'yellow') bg-yellow-500
                                    @else bg-orange-500
                                    @endif">
                                    {{ ucfirst($clink['template']) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b text-center">
                                <button wire:click="deleteClink({{ $clink['id'] }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus CLINK ini?"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 px-4 text-center text-gray-500">
                                Belum ada data CLINK. Tambahkan CLINK baru menggunakan form di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
