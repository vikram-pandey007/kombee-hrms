import './bootstrap';
import Alpine from 'alpinejs';
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

// PowerGrid imports
import './../../vendor/power-components/livewire-powergrid/dist/powergrid';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize Livewire with proper PowerGrid support
Livewire.start();

// PowerGrid initialization
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, initializing PowerGrid...');

    // Ensure PowerGrid is properly loaded
    if (window.PowerGrid) {
        console.log('PowerGrid loaded successfully');
    }

    // Force PowerGrid to reinitialize on page changes
    document.addEventListener('livewire:navigated', function () {
        console.log('Livewire navigated, reinitializing PowerGrid...');
        setTimeout(() => {
            if (window.PowerGrid) {
                console.log('PowerGrid reinitialized after navigation');
            }
        }, 100);
    });
});
