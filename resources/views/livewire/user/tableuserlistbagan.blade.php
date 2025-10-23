<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\UserBaganList;

new class extends Component {

    public function with()
    {
        return [
            'users' => UserBaganList::all()
        ];
    }

    public function openModalEdit($userId)
    {
//        debug($userId);
        $this->dispatch('OpenUserEditModal', $userId);
        $this->dispatch('reloadDataTable');
    }

    public function deleteUser($userId)
    {
        try {
            $user = UserBaganList::find($userId);

            if (!$user) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'User tidak ditemukan!',
                    'title' => 'Error'
                ]);
                return;
            }

            // Delete image file if exists
            if ($user->image_path) {
                \Storage::disk('public')->delete($user->image_path);
            }

            // Delete user record
            $user->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'User berhasil dihapus!',
                'title' => 'Berhasil'
            ]);

            // Reload DataTable after successful delete
            $this->dispatch('reloadDataTable');

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage(),
                'title' => 'Error'
            ]);
        }
    }

    #[On('RefreshUserBaganList')]
    public function refreshData()
    {
        // This will trigger a re-render and call with() method again
        $this->dispatch('reloadDataTable');
    }
}; ?>

<div>
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Data Member Bagan</h2>
        <p class="text-sm text-gray-600">Daftar semua Member dalam sistem bagan organisasi</p>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden p-4 w-full">
        <div class="overflow-x-auto max-w-full">
            <table id="userTable" class="w-full table-auto display nowrap" style="width:100%">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($user->image_path)
                                <img src="{{ Storage::url($user->image_path) }}"
                                     alt="{{ $user->nama }}"
                                     class="w-12 h-12 object-cover border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                {{ $user->nik }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                {{ $user->team }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="openModalEdit({{$user->id}})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="deleteUser({{ $user->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150"
                                        onclick="confirm('Anda yakin ingin menghapus user ini?') || event.stopImmediatePropagation()">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@assets
<script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
<link rel="stylesheet" href="{{asset("js/DataTables/datatables.css")}}">
@endassets

@pushonce('scripts-def')
    <script src="{{asset("js/DataTables/datatables.js")}}"></script>
@endpushonce

@script
<script>
    const option = {
        responsive: true,
        scrollX: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        language: {
            "emptyTable": "Tidak ada data member bagan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "lengthMenu": "Tampilkan _MENU_ data",
            "loadingRecords": "Memuat...",
            "processing": "Memproses...",
            "search": "Cari:",
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        order: [[3, 'asc']],
        columnDefs: [
            {
                targets: [1, 6], // Foto and Aksi columns
                orderable: false,
                searchable: false
            },
            {
                targets: [1], // Foto column
                width: "80px"
            },
            {
                targets: [6], // Aksi column
                width: "140px"
            }
        ],
        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4"<"mb-2 sm:mb-0"l><"mb-2 sm:mb-0"f>>' +
            '<"overflow-x-auto"t>' +
            '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4"<"mb-2 sm:mb-0"i><"mb-2 sm:mb-0"p>>',
        initComplete: function () {
            console.log('DataTable initialized successfully');
            // Add Tailwind classes to DataTables elements
            $('.dataTables_length select').addClass('px-3 py-1 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            $('.dataTables_filter input').addClass('px-3 py-1 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 mx-1 text-sm border border-gray-300 rounded hover:bg-gray-50');
            $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-600 text-white border-blue-600');
        }
    }

    let table = null;

    function initializeDataTable() {
        if ($.fn.DataTable.isDataTable('#userTable')) {
            $('#userTable').DataTable().destroy();
        }

        if (typeof $.fn.DataTable !== 'undefined') {
            table = $('#userTable').DataTable(option);
            console.log('DataTable initialized/reinitialized');
        } else {
            console.error('DataTables not loaded properly');
            // Add basic styling as fallback
            $('#userTable').addClass('w-full border-collapse');
            $('#userTable th, #userTable td').addClass('border border-gray-200 px-4 py-2 text-left');
            $('#userTable th').addClass('bg-gray-50 font-medium text-gray-900');
            $('#userTable tr:nth-child(even)').addClass('bg-gray-50');
        }
    }

    $(document).ready(function () {
        console.log('jQuery loaded:', typeof $);
        console.log('DataTable available:', typeof $.fn.DataTable);
        initializeDataTable();
    });

    // Listen for Livewire component updates
    document.addEventListener('livewire:navigated', function () {
        initializeDataTable();
    });

    // Listen for the reloadDataTable event
    document.addEventListener('reloadDataTable', function () {
        console.log('Reloading DataTable...');
        // For server-side rendered data, we need to wait for Livewire to re-render
        setTimeout(function() {
            initializeDataTable();
        }, 100);
    });
</script>
@endscript
