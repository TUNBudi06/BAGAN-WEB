<?php

use Livewire\Volt\Component;

new class extends Component {
    public $chartId = null;
    public $linkList = [];
    public $userBaganList = [];
    public $templateBaganList = [];
    public $subLevelList = [];

    // Form properties
    public $linkId = null;
    public $chart_id = '';
    public $chart_pid = '';
    public $chart_ppid = '';
    public $chart_stpid = '';
    public $type = 'text';
    public $user_id = '';
    public $name = '';
    public $nik = '';
    public $team = '';
    public $image = null;
    public $label = '';
    public $template_bagan_id = '';
    public $sub_level_id = '';
    public $node_type = '';

    public $isEditMode = false;
    public $isModalOpen = false;

    public function mount($idData){
        $this->chartId = $idData;
        $this->getLinkChartBindingVolt();
        $this->getUserBaganList();
        $this->getTemplateBaganList();
        $this->getSubLevelList();
    }

    public function getLinkChartBindingVolt(){
        $this->linkList = \App\Models\LinkChartEmbed::with('user')->where('bagan_list_id', $this->chartId)->get();
    }

    public function getUserBaganList(){
        $this->userBaganList = \App\Models\UserBaganList::orderBy('nama', 'asc')->get();
    }

    public function getTemplateBaganList(){
        $this->templateBaganList = \App\Models\templateBagan::all();
    }

    public function getSubLevelList(){
        $this->subLevelList = \App\Models\SubLevels::where('bagan_list_id', $this->chartId)->get();
    }

    public function updatedUserId($value){
        if($value && $this->type == 'user'){
            $user = \App\Models\UserBaganList::find($value);
            if($user){
                $this->name = $user->nama;
                $this->nik = $user->nik;
                $this->team = $user->team;
                $this->image = $user->image_path;
            }
        }
    }

    public function updatedType($value){
        // Kosongkan semua data user ketika type berubah dari user ke text
        if($value == 'text'){
            $this->user_id = '';
            $this->name = '';
            $this->nik = '';
            $this->team = '';
            $this->image = '';
        }
    }

    public function addLinkChartBindingVolt(){
        $this->resetForm();
        $this->isEditMode = false;
        $this->chart_id = $this->linkList->max('chart_id') + 1;
        $this->isModalOpen = true;
    }

    public function editLinkChart($id){
        $link = \App\Models\LinkChartEmbed::find($id);
        if($link){
            $this->linkId = $link->id;
            $this->chart_id = $link->chart_id;
            $this->chart_pid = $link->chart_pid;
            $this->chart_ppid = $link->chart_ppid;
            $this->chart_stpid = $link->chart_stpid;
            $this->type = $link->type;
            $this->user_id = $link->user_id;
            $this->name = $link->name ?: '';
            $this->nik = $link->nik ?: '';
            $this->team = $link->team ?: '';
            $this->image = $link->img ?: '';
            $this->label = $link->label;
            $this->template_bagan_id = $link->template_bagan_id;
            $this->sub_level_id = $link->sub_level_id;
            $this->node_type = $link->node_type;
            $this->isEditMode = true;
            $this->isModalOpen = true;
        }
    }

    public function saveLinkChart(){
        try {
            $this->validate([
                'chart_id' => 'required|integer',
                'type' => 'required|in:user,text',
                'user_id' => 'nullable|integer',
                'name' => 'nullable|string|max:255',
                'nik' => 'nullable|string|max:50',
                'team' => 'nullable|string|max:255',
                'image' => 'nullable|string|max:255',
                'chart_pid' => 'nullable|integer',
                'label' => 'nullable|string|max:255',
                'template_bagan_id' => 'nullable|integer',
                'sub_level_id' => 'nullable|integer',
                'node_type' => 'nullable|integer',
            ]);

            $data = [
                'bagan_list_id' => $this->chartId,
                'chart_id' => $this->chart_id,
                'chart_pid' => $this->chart_pid ?: null,
                'chart_ppid' => $this->chart_ppid ?: null,
                'chart_stpid' => $this->chart_stpid ?: null,
                'type' => $this->type,
                'user_id' => $this->user_id ?: null,
                'name' => $this->name ?: null,
                'nik' => $this->nik ?: null,
                'team' => $this->team ?: null,
                'img' => $this->image ?: null,
                'label' => $this->label,
                'template_bagan_id' => $this->template_bagan_id ?: null,
                'sub_level_id' => $this->sub_level_id ?: null,
                'node_type' => $this->node_type ?: null,
            ];

            if($this->isEditMode && $this->linkId){
                \App\Models\LinkChartEmbed::find($this->linkId)->update($data);
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Link Chart updated successfully.'
                ]);
            } else {
                \App\Models\LinkChartEmbed::create($data);
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Link Chart created successfully.'
                ]);
            }

            $this->resetForm();
            $this->getLinkChartBindingVolt();
            $this->dispatch('close-modal');

            // Dispatch event to refresh chart
            $this->dispatch('refreshChart');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->implode(', ')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error saving data: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteLinkChart($id){
        try {
            \App\Models\LinkChartEmbed::find($id)->delete();
            $this->getLinkChartBindingVolt();
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Link Chart deleted successfully.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error deleting data: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm(){
        $this->linkId = null;
        $this->chart_id = '';
        $this->chart_pid = '';
        $this->chart_ppid = '';
        $this->chart_stpid = '';
        $this->type = 'text';
        $this->user_id = '';
        $this->name = '';
        $this->nik = '';
        $this->team = '';
        $this->label = '';
        $this->template_bagan_id = '';
        $this->sub_level_id = '';
        $this->node_type = '';
    }
}; ?>

<div>
    <div x-data="{ modalOpen: @entangle('isModalOpen') }"
         @open-modal.window="modalOpen = true"
         @close-modal.window="modalOpen = false"
         @keydown.escape.window="modalOpen = false"
         class="relative z-50 w-auto h-auto">
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                <div x-show="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-black/40"></div>
                <div x-show="modalOpen"
                     x-trap.inert.noscroll="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative px-7 py-6 w-full bg-white sm:max-w-2xl sm:rounded-lg max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center pb-2">
                        <h3 class="text-lg font-semibold">{{ $isEditMode ? 'Edit Link Chart' : 'Add New Link Chart' }}</h3>
                        <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="relative w-auto">
                        <form wire:submit.prevent="saveLinkChart">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Chart ID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chart ID <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="chart_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('chart_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Chart PID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chart PID (Parent)</label>
                                    <input type="number" wire:model="chart_pid" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('chart_pid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Chart PPID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chart PPID</label>
                                    <input type="number" wire:model="chart_ppid" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('chart_ppid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Chart STPID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chart STPID</label>
                                    <input type="number" wire:model="chart_stpid" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('chart_stpid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                                    <select wire:model.live.debounce="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="text">Text</option>
                                        <option value="user">User</option>
                                    </select>
                                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                @if($type=='user')
                                    <!-- User ID -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                                        <select id="select2Form" wire:model.live="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">-- Select User --</option>
                                            @foreach($this->userBaganList as $user)
                                                <option value="{{ $user->id }}">{{ $user->nama }} (NIK: {{ $user->nik }}) - {{ $user->team }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <!-- Name Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" wire:model="name" @if($type == 'user') readonly @endif
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter name">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @if($type=='user')
                                        <p class="text-xs text-gray-500 mt-1">Auto-filled from selected user</p>
                                    @endif
                                </div>

                                @if($type=='user')
                                    <!-- NIK Field -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                        <input type="text" wire:model="nik" readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="NIK will be auto-filled">
                                        @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Team Field -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Team</label>
                                        <input type="text" wire:model="team" readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="Team will be auto-filled">
                                        @error('team') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <!-- Label -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                    <input type="text" wire:model="label" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Template Bagan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Template Bagan</label>
                                    <select wire:model="template_bagan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Template --</option>
                                        @foreach($templateBaganList as $template)
                                            @if($template->type == 'card')
                                                <option value="{{ $template->id }}">{{ $template->name }} ({{ $template->template }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('template_bagan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Sub Level ID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Level</label>
                                    <select wire:model="sub_level_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Sub Level --</option>
                                        @foreach($subLevelList as $subLevel)
                                            <option value="{{ $subLevel->id }}">{{ $subLevel->name }} (Value: {{ $subLevel->value }})</option>
                                        @endforeach
                                    </select>
                                    @error('sub_level_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Node Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Node Type</label>
                                    <select wire:model.live.debounce="node_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Node Type --</option>
                                        @foreach($templateBaganList as $template)
                                            @if($template->type == 'type')
                                                <option value="{{ $template->id }}">{{ $template->name }} ({{ $template->type }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('node_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 mt-6">
                                <button type="button" @click="modalOpen=false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ $isEditMode ? 'Update' : 'Save' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="container mx-auto p-4">
        <h3 class="text-2xl font-bold mb-2">List Chart Link</h3>
        <span class="text-sm text-gray-500">List to make Chart View editor</span>

        <button wire:click="addLinkChartBindingVolt" class="mt-4 w-full mb-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <span class="inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Link Chart
            </span>
        </button>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg overflow-y-scroll max-h-[500px]">
            <table id="linkChartTable" class="min-w-full divide-y divide-gray-200 display">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PPID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STPID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Node Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($linkList as $link)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                {{ $link->chart_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $link->chart_pid ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $link->chart_ppid ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $link->chart_stpid ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $link->type == 'user' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($link->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($link->type == 'user' && $link->user)
                                    {{ $link->user->nama }}
                                @elseif($link->type == 'text')
                                    {{ $link->name ?: '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($link->type == 'user' && $link->user)
                                    {{ $link->user->nik }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($link->type == 'user' && $link->user)
                                    {{ $link->user->team }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $link->label ?: '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $link->getTemplateBagan ? $link->getTemplateBagan->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $link->getSubLevel ? $link->getSubLevel->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $link->getNodeType ? $link->getNodeType->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editLinkChart({{ $link->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteLinkChart({{ $link->id }})"
                                        wire:confirm="Are you sure you want to delete this link chart?"
                                        class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">
                                No link charts found. Click "Add New Link Chart" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@assets
<link href="{{asset("js/select2/select2.min.css")}}" rel="stylesheet" />
@endassets
@pushonce('scripts-def')
    <script src="{{asset('js/select2/select2.min.js')}}"></script>
@endpushonce

