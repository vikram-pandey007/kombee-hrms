<?php

// Simple test script to verify validation rules
require_once 'vendor/autoload.php';

use App\Models\Employee;

echo "=== Employee Validation Test ===\n\n";

// Test validation rules
$rules = Employee::rules();
$messages = Employee::messages();

echo "Validation Rules:\n";
foreach ($rules as $field => $rule) {
    echo "- {$field}: " . (is_array($rule) ? implode('|', $rule) : $rule) . "\n";
}

echo "\nValidation Messages:\n";
foreach ($messages as $field => $message) {
    echo "- {$field}: {$message}\n";
}

echo "\n=== Test Cases ===\n";

// Test case 1: Empty data
echo "\nTest 1: Empty data\n";
$testData = [
    'name' => '',
    'email' => '',
    'department_id' => '',
    'designation_id' => '',
    'joining_date' => '',
    'fixed_salary' => '',
    'status' => ''
];

// Test case 2: Invalid email
echo "\nTest 2: Invalid email\n";
$testData2 = [
    'name' => 'John Doe',
    'email' => 'invalid-email',
    'department_id' => '1',
    'designation_id' => '1',
    'joining_date' => '2025-01-01',
    'fixed_salary' => '50000',
    'status' => 'Active'
];

// Test case 3: Future date
echo "\nTest 3: Future joining date\n";
$testData3 = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'department_id' => '1',
    'designation_id' => '1',
    'joining_date' => '2030-01-01',
    'fixed_salary' => '50000',
    'status' => 'Active'
];

echo "\nValidation test completed. Check the rules and messages above.\n";
echo "To test actual validation, try creating an employee with invalid data in the UI.\n";
