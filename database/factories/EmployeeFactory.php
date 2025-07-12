<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = ['Engineering', 'Marketing', 'Sales', 'Human Resources', 'Finance', 'Operations', 'Customer Support'];
        $designations = [
            'Software Engineer',
            'Senior Software Engineer',
            'Product Manager',
            'Marketing Specialist',
            'Sales Executive',
            'Accountant',
            'HR Manager',
            'Operations Manager',
            'Customer Support Executive'
        ];

        return [
            'employee_id' => 'EMP-' . fake()->unique()->randomNumber(4, true),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+91' . fake()->randomNumber(10, true),
            'department' => fake()->randomElement($departments),
            'designation' => fake()->randomElement($designations),
            'joining_date' => fake()->dateTimeBetween('-3 years', 'now'),
            'status' => fake()->randomElement(['Active', 'Active', 'Active', 'Active', 'Inactive']), // 80% active
            'fixed_salary' => fake()->randomFloat(2, 25000, 120000), // Monthly salary in INR
        ];
    }
}
