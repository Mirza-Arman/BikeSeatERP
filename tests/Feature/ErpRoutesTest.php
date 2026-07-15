<?php

use App\Models\User;

it('redirects guests from erp routes to login', function (string $uri) {
    $this->get($uri)->assertRedirect(route('login'));
})->with([
    'dashboard' => '/dashboard',
    'users' => '/user-management/users',
    'employees' => '/employees',
    'suppliers' => '/suppliers',
    'raw materials' => '/raw-materials',
    'production orders' => '/production/orders',
    'inventory stock' => '/inventory/stock',
    'customers' => '/customers',
    'reports' => '/reports',
    'settings' => '/settings',
]);

it('redirects the home page to the dashboard for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('dashboard'));
});

it('renders erp module pages for authenticated users', function (string $uri, string $expectedText) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get($uri)
        ->assertSuccessful()
        ->assertSee($expectedText);
})->with([
    'dashboard' => ['/dashboard', 'Recent Activities'],
    'users' => ['/user-management/users', 'Users'],
    'roles' => ['/user-management/roles', 'Roles'],
    'employees' => ['/employees', 'Employees'],
    'suppliers' => ['/suppliers', 'Suppliers'],
    'raw materials' => ['/raw-materials', 'Raw Materials'],
    'production' => ['/production/orders', 'Production Orders'],
    'inventory' => ['/inventory/finished-goods', 'Finished Goods'],
    'customers' => ['/customers', 'Customers'],
    'reports' => ['/reports', 'Reports'],
    'settings' => ['/settings', 'Settings'],
]);

it('renders the erp master layout components', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSuccessful()
        ->assertSee(config('erp.name'))
        ->assertSee('Sample Data Table')
        ->assertSee('Sample Form');
});
