<?php

use Illuminate\Support\Facades\Route;

it('registers the ERP routes used by the repaired CRUD modules', function () {
    $routes = collect(Route::getRoutes()->getRoutesByName())
        ->keys()
        ->filter(fn (string $name) => str_starts_with($name, 'erp.'))
        ->filter(fn (string $name) => str_contains($name, 'purchase-orders')
            || str_contains($name, 'orders.')
            || str_contains($name, 'products.')
            || str_contains($name, 'categories.'));

    expect($routes->contains('erp.suppliers.purchase-orders.index'))->toBeTrue();
    expect($routes->contains('erp.suppliers.purchase-orders.store'))->toBeTrue();
    expect($routes->contains('erp.production.orders.index'))->toBeTrue();
    expect($routes->contains('erp.production.orders.store'))->toBeTrue();
    expect($routes->contains('erp.inventory.products.index'))->toBeTrue();
    expect($routes->contains('erp.inventory.products.store'))->toBeTrue();
    expect($routes->contains('erp.raw-materials.categories.index'))->toBeTrue();
    expect($routes->contains('erp.raw-materials.categories.store'))->toBeTrue();
    expect($routes->contains('erp.customers.orders.index'))->toBeTrue();
    expect($routes->contains('erp.customers.orders.store'))->toBeTrue();
});
