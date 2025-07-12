<div class="mt-6 space-y-4">
    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input wire:model="name" id="name" type="text" class="mt-1 block w-full" data-testid="employee-name-input" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <!-- Email -->
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" data-testid="employee-email-input" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <!-- Phone -->
    <div>
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input wire:model="phone" id="phone" type="text" class="mt-1 block w-full" data-testid="employee-phone-input" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    <!-- Department -->
    <div>
        <x-input-label for="department" :value="__('Department')" />
        <x-text-input wire:model="department" id="department" type="text" class="mt-1 block w-full" data-testid="employee-department-input" />
        <x-input-error class="mt-2" :messages="$errors->get('department')" />
    </div>
    <!-- Designation -->
    <div>
        <x-input-label for="designation" :value="__('Designation')" />
        <x-text-input wire:model="designation" id="designation" type="text" class="mt-1 block w-full" data-testid="employee-designation-input" />
        <x-input-error class="mt-2" :messages="$errors->get('designation')" />
    </div>
    <!-- Joining Date -->
    <div>
        <x-input-label for="joining_date" :value="__('Joining Date')" />
        <x-text-input wire:model="joining_date" id="joining_date" type="date" class="mt-1 block w-full" data-testid="employee-joining-date-input" />
        <x-input-error class="mt-2" :messages="$errors->get('joining_date')" />
    </div>
    <!-- Fixed Salary -->
    <div>
        <x-input-label for="fixed_salary" :value="__('Fixed Salary')" />
        <x-text-input wire:model="fixed_salary" id="fixed_salary" type="number" step="0.01" class="mt-1 block w-full" data-testid="employee-salary-input" />
        <x-input-error class="mt-2" :messages="$errors->get('fixed_salary')" />
    </div>
</div>