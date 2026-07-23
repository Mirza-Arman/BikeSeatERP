<?php

use Illuminate\Support\Facades\Route;

it('registers the ERP routes used by the CRUD modules', function () {
    expect(Route::has('purchases.purchase-orders.index'))->toBeTrue();
    expect(Route::has('purchases.purchase-orders.store'))->toBeTrue();
    expect(Route::has('erp.production.orders.index'))->toBeTrue();
    expect(Route::has('erp.production.orders.store'))->toBeTrue();
    expect(Route::has('erp.inventory.products.index'))->toBeTrue();
    expect(Route::has('erp.inventory.products.store'))->toBeTrue();
    expect(Route::has('erp.raw-materials.categories.index'))->toBeTrue();
    expect(Route::has('erp.raw-materials.categories.store'))->toBeTrue();
    expect(Route::has('erp.customers.orders.index'))->toBeTrue();
    expect(Route::has('erp.customers.orders.store'))->toBeTrue();
});

it('registers static customer and supplier routes before parameterized routes', function () {
    $routes = collect(Route::getRoutes()->getRoutes());

    $customerRoutes = $routes->filter(fn ($route) => str_starts_with($route->uri(), 'customers'));
    $ordersIndexPosition = $customerRoutes->search(fn ($route) => $route->getName() === 'erp.customers.orders.index');
    $customerShowPosition = $customerRoutes->search(fn ($route) => $route->getName() === 'erp.customers.show');

    expect($ordersIndexPosition)->toBeLessThan($customerShowPosition);

    $supplierRoutes = $routes->filter(fn ($route) => str_starts_with($route->uri(), 'suppliers'));
    $purchaseHistoryPosition = $supplierRoutes->search(fn ($route) => $route->getName() === 'erp.suppliers.purchase-history.index');
    $supplierShowPosition = $supplierRoutes->search(fn ($route) => $route->getName() === 'erp.suppliers.show');

    expect($purchaseHistoryPosition)->toBeLessThan($supplierShowPosition);
});
