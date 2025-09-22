<x-filament::page>
    <div class="space-y-4">
        <!-- Scanner input field -->
        <div class="relative">
            <input type="text"
                   wire:model.live.debounce.500ms="search"
                   id="scanInput"
                   placeholder="Scan / Enter Barcode or Serial Number"
                   class="w-full p-4 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-mono"
                   style="color: black;"
                   autofocus
                   autocomplete="off"
            />
            
            @if($search)
                <button wire:click="clearSearch" 
                        class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            @endif
        </div>

        <!-- Loading indicator -->
        @if($searching)
            <div class="p-4 text-center">
                <div class="inline-flex items-center">
                    <x-filament::loading-indicator class="w-5 h-5 mr-2" />
                    <span class="text-gray-600">Searching...</span>
                </div>
            </div>
        @endif

        <!-- Display record -->
        @if($record)
            <div class="p-6 border rounded-lg bg-success-50 border-success-200 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div><strong class="text-gray-700">Item Name:</strong> {{ $record->name }}</div>
                        <div><strong class="text-gray-700">Serial Number:</strong> {{ $record->serial_number }}</div>
                        <div><strong class="text-gray-700">Barcode:</strong> {{ str_replace('SN CODE : SN', '', $record->barcode) }}</div>
                        <div><strong class="text-gray-700">Issue Place:</strong> {{ $record->issue_place }}</div>
                    </div>
                    <div class="space-y-2">
                        <div><strong class="text-gray-700">Signal Unit:</strong> {{ $record->signal_unit }}</div>
                        <div><strong class="text-gray-700">Issue Type:</strong> {{ $record->issuing_type ?? 'N/A' }}</div>
                        <div><strong class="text-gray-700">Issue Date:</strong> {{ $record->issue_date }}</div>
                        <div><strong class="text-gray-700">Warranty Expiry:</strong> {{ $record->warrenty_expiry_date ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        @elseif($search && !$searching)
            <div class="p-4 border rounded-lg bg-danger-50 border-danger-200 text-center">
                <div class="text-danger-600">
                    <x-heroicon-o-magnifying-glass class="w-8 h-8 mx-auto mb-2" />
                    <p>No item found for: "{{ $search }}"</p>
                </div>
            </div>
        @endif
    </div>

    <!-- JavaScript for better UX -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const input = document.getElementById('scanInput');
            
            // Focus on input when page loads
            input.focus();
            
            // Listen for clear event
            Livewire.on('search-cleared', () => {
                input.value = '';
                input.focus();
            });
            
            // Prevent form submission on enter
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-filament::page>