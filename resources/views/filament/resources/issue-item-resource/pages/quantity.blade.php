<x-filament-panels::page>
    <x-filament::section>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Quantity Card -->
            <div class="fi-card flex flex-col gap-y-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-2">
                    <x-heroicon-o-cube class="h-5 w-5 text-primary-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Quantity</span>
                </div>
                <div class="text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{ $this->itemData['totalCount'] }}
                </div>
            </div>

            <!-- Received Card -->
            <div class="fi-card flex flex-col gap-y-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-2">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-success-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Received</span>
                </div>
                <div class="text-2xl font-semibold tracking-tight text-success-600 dark:text-success-400">
                    {{ $this->itemData['receivedCount'] }}
                </div>
            </div>

            <!-- Issued Card -->
            <div class="fi-card flex flex-col gap-y-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-2">
                    <x-heroicon-o-arrow-up-circle class="h-5 w-5 text-warning-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Issued</span>
                </div>
                <div class="text-2xl font-semibold tracking-tight text-warning-600 dark:text-warning-400">
                    {{ $this->itemData['issuedCount'] }}
                </div>
            </div>

            <!-- Available Card -->
            <div class="fi-card flex flex-col gap-y-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-x-2">
                    <x-heroicon-o-archive-box class="h-5 w-5 text-primary-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Available</span>
                </div>
                <div class="text-2xl font-semibold tracking-tight text-primary-600 dark:text-primary-400">
                    {{ $this->itemData['availableCount'] }}
                </div>
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Serial Numbers</h2>
            <x-filament::button wire:click="exportIssuedSerialNumbers" icon="heroicon-o-document-arrow-down">
                Export to Excel
            </x-filament::button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Serial Number</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Signal Unit</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Issue Place</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Warranty Expiry</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @foreach($this->itemData['serialNumbers'] as $serial)
                        <tr class="bg-white dark:bg-gray-900">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $serial->serial_number }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                               @php
   
    
        $statusColor = match(true) {
            $serial->issued == 1 => 'danger',   // Issued
            default              => 'warning',  // Available
        };

        $statusText = match(true) {
            $serial->issued == 1 => 'Issued',
            default              => 'Available',
        };
    @endphp

    <span class="fi-badge fi-color-{{ $statusColor }} inline-flex items-center gap-x-1 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset">
        {{ $statusText }}
    </span>
               



    
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $serial->issuing_type?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $serial->signal_unit_name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $serial->issue_place ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $serial->warrenty_expiry_date ? \Carbon\Carbon::parse($serial->warrenty_expiry_date)->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($this->itemData['serialNumbers']) === 0)
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <x-heroicon-o-document-magnifying-glass class="mx-auto h-12 w-12" />
                <p class="mt-4 text-sm">No serial numbers found.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>