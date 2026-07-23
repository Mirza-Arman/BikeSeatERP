<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Models\User;

it('renders dashboard with live metrics from the database', function () {
    $user = User::factory()->create();

    Employee::create([
        'employee_code' => 'EMP-001',
        'full_name' => 'Test Employee',
        'father_name' => 'Test Father',
        'cnic' => '12345-1234567-1',
        'phone' => '03001234567',
        'email' => 'employee@example.com',
        'address' => 'Test address',
        'joining_date' => now()->toDateString(),
        'department_id' => null,
        'designation' => 'Technician',
        'salary' => 1000,
        'status' => 'active',
        'created_by' => $user->id,
    ]);

    Supplier::create([
        'supplier_code' => 'SUP-999',
        'company_name' => 'Live Supplier',
        'contact_person' => 'Supplier Contact',
        'phone' => '03009876543',
        'email' => 'supplier@example.com',
        'address' => 'Supplier Lane',
        'city' => 'Town',
        'balance' => 0,
        'status' => 'active',
    ]);

    Customer::create([
        'customer_code' => 'CUS-999',
        'customer_name' => 'Live Customer',
        'phone' => '03001112233',
        'email' => 'customer@example.com',
        'address' => 'Customer Street',
        'city' => 'City',
        'balance' => 0,
    ]);

    $product = Product::create([
        'product_code' => 'PRD-999',
        'product_name' => 'Live Product',
        'model' => 'X1',
        'selling_price' => 20,
        'minimum_stock' => 5,
        'current_stock' => 10,
        'status' => 'active',
    ]);

    RawMaterial::create([
        'material_code' => 'RM-999',
        'name' => 'Live Material',
        'unit' => 'pcs',
        'minimum_stock' => 2,
        'current_stock' => 1,
        'cost_per_unit' => 5,
        'status' => 'active',
    ]);

    PurchaseOrder::create([
        'purchase_number' => 'PO-000999',
        'supplier_id' => Supplier::first()->id,
        'purchase_date' => now()->toDateString(),
        'invoice_no' => 'INV-999',
        'grand_total' => 100,
        'paid_amount' => 100,
        'remaining_amount' => 0,
        'status' => 'pending',
    ]);

    ProductionOrder::create([
        'order_no' => 'PRO-999',
        'product_id' => $product->id,
        'formula_id' => null,
        'quantity_to_produce' => 5,
        'production_date' => now()->toDateString(),
        'status' => 'pending',
        'remarks' => 'Test production',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSuccessful()
        ->assertSee('Total Employees')
        ->assertSee('1')
        ->assertSee('Total Suppliers')
        ->assertSee('Total Customers')
        ->assertSee('Products')
        ->assertSee('Raw Materials')
        ->assertSee('Production Today')
        ->assertSee('Low Stock');
});
