<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Corrective Action Report') }}
        </h2>
        <div>
            <a href="{{ route('cars.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700">
                {{ __('Kembali ke Daftar') }}
            </a>
        </div>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 text-sm">

                {{-- Main Document Container --}}
                <div class="border border-black dark:border-gray-500">

                    {{-- Header Section --}}
                    <div class="flex border-b border-black dark:border-gray-500">
                        {{-- BCS Logo --}}
                        <div class="w-1/3 flex items-center justify-center p-4 border-r border-black dark:border-gray-500">
                            <img src="https://bcs-logistics.co.id/assets/images/logoo.png" alt="BCS Logistics" class="h-12">
                        </div>

                        {{-- Title --}}
                        <div class="w-1/3 flex flex-col items-center justify-center p-4 border-r border-black dark:border-gray-500">
                            <h3 class="font-bold text-lg">Corrective Action Report</h3>
                            <p class="mt-2">Nomor : {{ $car->number }}</p>
                        </div>

                        {{-- Info Box --}}
                        <div class="w-1/3 p-2">
                            <div class="flex justify-end text-xs font-mono">
                                No. FM-QAS-06 Rev.01
                            </div>
                            <div class="space-y-1 mt-1">
                                <p class="font-bold">Sumber informasi: <span class="font-normal text-xs italic">(Beri tanda V dalam lingkaran yang sesuai)</span></p>
                                <div>
                                    <span class="inline-block w-4 h-4 border border-black dark:border-gray-400 text-center leading-4">
                                        {{ $car->source_of_information == 'external' ? 'V' : '' }}
                                    </span>
                                    <span>Eksternal (Klaim / Complain Customer)</span>
                                </div>
                                <div>
                                    <span class="inline-block w-4 h-4 border border-black dark:border-gray-400 text-center leading-4">
                                        {{ $car->source_of_information == 'internal' ? 'V' : '' }}
                                    </span>
                                    <span>Internal (Masalah intern / antar bagian)</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 mt-2 border-t border-black dark:border-gray-500 pt-1">
                                <div>
                                    <p>Diterbitkan tanggal:</p>
                                    <p class="font-semibold">{{ $car->issued_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p>Diterbitkan oleh:</p>
                                    <p class="font-semibold">{{ $car->issuer->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Problem Finding Section --}}
                    <div class="flex border-b border-black dark:border-gray-500">
                        {{-- Left Side --}}
                        <div class="w-1/2 border-r border-black dark:border-gray-500 p-2 space-y-2">
                            <h4 class="font-bold text-center">TEMUAN MASALAH</h4>
                            <div class="space-y-1">
                                <p><strong>Dilaporkan / disampaikan oleh:</strong> {{ $car->rootCauseAnalysis->accident->user->name ?? 'N/A' }}</p>
                                <p><strong>Nama Jabatan & Divisi / Perusahaan:</strong> -</p>
                                <p><strong>Hari, Tanggal & Jam:</strong> {{ $car->rootCauseAnalysis->accident->accident_date->format('l, d F Y H:i') }}</p>
                            </div>
                            <hr class="dark:border-gray-600">
                            <div class="space-y-1">
                                <p><strong>Didaftarkan oleh:</strong> {{ $car->issuer->name ?? 'N/A' }}</p>
                                <p><strong>Nama Jabatan & Divisi / Perusahaan:</strong> -</p>
                                <p><strong>Hari, Tanggal & Jam:</strong> {{ $car->created_at->format('l, d F Y H:i') }}</p>
                            </div>
                        </div>
                        {{-- Right Side --}}
                        <div class="w-1/2 p-2">
                            <h4 class="font-bold text-center">URAIAN MASALAH</h4>
                            <p class="mt-2">{{ $car->rootCauseAnalysis->accident->description }}</p>
                            @if($car->rootCauseAnalysis->accident->photo_path)
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    <img src="{{ Storage::url($car->rootCauseAnalysis->accident->photo_path) }}" alt="Lampiran" class="max-w-full h-auto rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Actions Table --}}
                    <div>
                        <table class="min-w-full text-xs">
                            <thead class="text-center font-bold">
                                <tr>
                                    <td class="border border-black dark:border-gray-500 p-1 w-8">NO</td>
                                    <td class="border border-black dark:border-gray-500 p-1">AKAR PERMASALAHAN<br>URAIAN</td>
                                    <td class="border border-black dark:border-gray-500 p-1">TINDAKAN PERBAIKAN</td>
                                    <td class="border border-black dark:border-gray-500 p-1 w-24">TARGET</td>
                                    <td class="border border-black dark:border-gray-500 p-1 w-24">P.I.C.</td>
                                    <td class="border border-black dark:border-gray-500 p-1 w-20">PARAF</td>
                                    <td class="border border-black dark:border-gray-500 p-1">HASIL VERIFIKASI</td>
                                    <td class="border border-black dark:border-gray-500 p-1 w-24">AUDITOR</td>
                                    <td class="border border-black dark:border-gray-500 p-1 w-20">PARAF</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($car->actions as $index => $action)
                                    <tr>
                                        <td class="border border-black dark:border-gray-500 p-1 text-center">{{ $index + 1 }}</td>
                                        <td class="border border-black dark:border-gray-500 p-1">{{ $car->rootCauseAnalysis->accident->description }}</td>
                                        <td class="border border-black dark:border-gray-500 p-1">{{ $action->description }}</td>
                                        <td class="border border-black dark:border-gray-500 p-1 text-center">{{ $action->due_date->format('d/m/Y') }}</td>
                                        <td class="border border-black dark:border-gray-500 p-1 text-center">{{ $action->pic->name ?? 'N/A' }}</td>
                                        <td class="border border-black dark:border-gray-500 p-1 h-16"></td>
                                        <td class="border border-black dark:border-gray-500 p-1">
                                            @if($action->verification_attachment_path)
                                                <img src="{{ Storage::url($action->verification_attachment_path) }}" alt="Hasil Verifikasi" class="max-w-full h-auto rounded mb-1">
                                            @endif
                                            {{ $action->verification_notes }}
                                        </td>
                                        <td class="border border-black dark:border-gray-500 p-1 text-center">{{ $action->verifier->name ?? '' }}</td>
                                        <td class="border border-black dark:border-gray-500 p-1"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="border border-black dark:border-gray-500 p-4 text-center">Tidak ada tindakan perbaikan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer Section --}}
                    <div class="flex border-t border-black dark:border-gray-500">
                        <div class="w-2/3 border-r border-black dark:border-gray-500 p-2 space-y-1">
                            <p><strong>Status Tindak Lanjut:</strong></p>
                             <div>
                                <span class="inline-block w-4 h-4 border border-black dark:border-gray-400 text-center leading-4">
                                     {{ $car->status == 'continued' ? 'V' : '' }}
                                </span>
                                <span>Dilanjutkan (CAR baru)</span>
                            </div>
                            <div>
                                <span class="inline-block w-4 h-4 border border-black dark:border-gray-400 text-center leading-4">
                                     {{ $car->status == 'open' ? 'V' : '' }}
                                </span>
                                <span>Tidak Selesai / Open</span>
                            </div>
                            <div>
                                <span class="inline-block w-4 h-4 border border-black dark:border-gray-400 text-center leading-4">
                                     {{ $car->status == 'closed' ? 'V' : '' }}
                                </span>
                                <span>Selesai (Tuntas)</span>
                            </div>
                        </div>
                        <div class="w-1/3 p-2">
                             <p><strong>Catatan Manajemen:</strong></p>
                             <p class="mt-1 h-16">{{ $car->management_notes ?: '' }}</p>
                        </div>
                    </div>

                    {{-- Distribution & Approval --}}
                    <div class="flex border-t border-black dark:border-gray-500">
                        {{-- Lembar Distribusi --}}
                        <div class="w-1/2 border-r border-black dark:border-gray-500 p-2">
                             <h4 class="font-bold text-center mb-1">Lembar Distribusi</h4>
                            <table class="w-full text-xs text-center">
                                <thead>
                                    <tr class="font-bold">
                                        <td class="border border-black dark:border-gray-500">Area</td>
                                        <td class="border border-black dark:border-gray-500">Paraf</td>
                                        <td class="border border-black dark:border-gray-500">Tanggal</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-black dark:border-gray-500 p-1 text-left">Operational</td>
                                        <td class="border border-black dark:border-gray-500 h-10"></td>
                                        <td class="border border-black dark:border-gray-500"></td>
                                    </tr>
                                    <tr>
                                        <td class="border border-black dark:border-gray-500 p-1 text-left">QA & HSE</td>
                                        <td class="border border-black dark:border-gray-500 h-10"></td>
                                        <td class="border border-black dark:border-gray-500"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- Pengesahan --}}
                        <div class="w-1/2 p-2">
                             <h4 class="font-bold text-center mb-1">PENGESAHAN</h4>
                             <div class="flex justify-around items-end mt-2">
                                <div class="text-center">
                                    <div class="border border-black dark:border-gray-500 h-24 w-32 flex justify-center items-center font-mono text-2xl text-gray-300">
                                        +
                                    </div>
                                    <p class="mt-1 font-bold">MR</p>
                                </div>
                                <div class="text-center">
                                    <div class="border border-black dark:border-gray-500 h-24 w-32 flex justify-center items-center font-mono text-2xl text-gray-300">
                                        +
                                    </div>
                                    <p class="mt-1 font-bold">DIREKTUR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
