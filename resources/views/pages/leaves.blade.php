<x-app-layout>
    <x-slot name="header">
        {{ __('Leave Management') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @livewire('leave-manager')
                </div>
            </div>
        </div>
    </div>

    <!-- PowerGrid Scripts -->
    <script>
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded for leaves page');
            
            // Force PowerGrid action buttons to show after a delay
            setTimeout(() => {
                const actionButtons = document.querySelectorAll('[data-testid*="leave-row-action"]');
                console.log('Found action buttons:', actionButtons.length);
                actionButtons.forEach(btn => {
                    btn.style.display = 'inline-flex';
                    btn.style.visibility = 'visible';
                    btn.style.opacity = '1';
                });
            }, 2000);
        });
    </script>
</x-app-layout>