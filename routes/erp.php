<?php

use App\Http\Controllers\Customers\DeliveryRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employees\AttendanceController;
use App\Http\Controllers\Employees\DepartmentController;
use App\Http\Controllers\Erp\CustomerController;
use App\Http\Controllers\Erp\CustomerOrderController;
use App\Http\Controllers\Erp\EmployeeController as ErpEmployeeController;
use App\Http\Controllers\Erp\MaterialCategoryController;
use App\Http\Controllers\Erp\ProductController;
use App\Http\Controllers\Erp\ProductionOrderController as ErpProductionOrderController;
use App\Http\Controllers\Erp\RawMaterialController;
use App\Http\Controllers\Erp\SupplierController;
use App\Http\Controllers\Inventory\FinishedGoodController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\StockTransactionController;
use App\Http\Controllers\Production\DailyProductionController;
use App\Http\Controllers\Production\MaterialConsumptionController;
use App\Http\Controllers\Production\ProductionFormulaController;
use App\Http\Controllers\Production\WorkerController;
use App\Http\Controllers\Purchases\GoodsReceiptController;
use App\Http\Controllers\Purchases\PaymentController;
use App\Http\Controllers\Purchases\PurchaseOrderController as NewPurchaseOrderController;
use App\Http\Controllers\Purchases\SupplierLedgerController;
use App\Http\Controllers\RawMaterials\StockAdjustmentController;
use App\Http\Controllers\RawMaterials\StockLedgerController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Suppliers\PurchaseHistoryController;
use App\Http\Controllers\UserManagement\PermissionController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('user-management')->name('user-management.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    });

    Route::prefix('employees')->name('erp.employees.')->group(function () {
        Route::get('/', [ErpEmployeeController::class, 'index'])->name('index');
        Route::get('/create', [ErpEmployeeController::class, 'create'])->name('create');
        Route::post('/', [ErpEmployeeController::class, 'store'])->name('store');
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/{employee}', [ErpEmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [ErpEmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [ErpEmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [ErpEmployeeController::class, 'destroy'])->name('destroy');
        Route::post('/{employee}/toggle-status', [ErpEmployeeController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('suppliers')->name('erp.suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::post('/payments', [SupplierController::class, 'storePayment'])->name('store-payment');
        Route::get('/purchase-history', [PurchaseHistoryController::class, 'index'])->name('purchase-history.index');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
        Route::post('/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{supplier}/ledger', [SupplierController::class, 'ledger'])->name('ledger');
        Route::get('/{supplier}/materials-supplied', [SupplierController::class, 'materialsSupplied'])->name('materials-supplied');
        Route::get('/{supplier}/create-payment', [SupplierController::class, 'createPayment'])->name('create-payment');
    });

    Route::prefix('raw-materials')->name('erp.raw-materials.')->group(function () {
        Route::get('/', [RawMaterialController::class, 'index'])->name('index');
        Route::get('/create', [RawMaterialController::class, 'create'])->name('create');
        Route::post('/', [RawMaterialController::class, 'store'])->name('store');
        Route::get('/categories', [MaterialCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [MaterialCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [MaterialCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [MaterialCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [MaterialCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [MaterialCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/stock-ledger', [StockLedgerController::class, 'index'])->name('stock-ledger.index');
        Route::get('/stock-adjustments', [StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');
        Route::get('/{rawMaterial}', [RawMaterialController::class, 'show'])->name('show');
        Route::get('/{rawMaterial}/edit', [RawMaterialController::class, 'edit'])->name('edit');
        Route::put('/{rawMaterial}', [RawMaterialController::class, 'update'])->name('update');
        Route::delete('/{rawMaterial}', [RawMaterialController::class, 'destroy'])->name('destroy');
        Route::post('/{rawMaterial}/toggle-status', [RawMaterialController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('production')->name('erp.production.')->group(function () {
        Route::get('/orders', [ErpProductionOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [ErpProductionOrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [ErpProductionOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{productionOrder}', [ErpProductionOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{productionOrder}/status', [ErpProductionOrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{productionOrder}/workers', [ErpProductionOrderController::class, 'assignWorkers'])->name('orders.assign-workers');
        Route::delete('/orders/{productionOrder}', [ErpProductionOrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
        Route::get('/daily', [DailyProductionController::class, 'index'])->name('daily.index');
        Route::get('/formula', [ProductionFormulaController::class, 'index'])->name('formula.index');
        Route::get('/formula/create', [ProductionFormulaController::class, 'create'])->name('formula.create');
        Route::post('/formula', [ProductionFormulaController::class, 'store'])->name('formula.store');
        Route::get('/formula/{formula}', [ProductionFormulaController::class, 'show'])->name('formula.show');
        Route::get('/formula/{formula}/edit', [ProductionFormulaController::class, 'edit'])->name('formula.edit');
        Route::put('/formula/{formula}', [ProductionFormulaController::class, 'update'])->name('formula.update');
        Route::delete('/formula/{formula}', [ProductionFormulaController::class, 'destroy'])->name('formula.destroy');
        Route::get('/material-consumption', [MaterialConsumptionController::class, 'index'])->name('material-consumption.index');
    });

    Route::prefix('inventory')->name('erp.inventory.')->group(function () {
        Route::get('/finished-goods', [FinishedGoodController::class, 'index'])->name('finished-goods.index');
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/transactions', [StockTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    });

    Route::prefix('customers')->name('erp.customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [CustomerOrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{customerOrder}', [CustomerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{customerOrder}/status', [CustomerOrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('/orders/{customerOrder}', [CustomerOrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/delivery-records', [DeliveryRecordController::class, 'index'])->name('delivery-records.index');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
            Route::get('/', [NewPurchaseOrderController::class, 'index'])->name('index');
            Route::get('/create', [NewPurchaseOrderController::class, 'create'])->name('create');
            Route::post('/', [NewPurchaseOrderController::class, 'store'])->name('store');
            Route::get('/{purchaseOrder}', [NewPurchaseOrderController::class, 'show'])->name('show');
            Route::get('/{purchaseOrder}/edit', [NewPurchaseOrderController::class, 'edit'])->name('edit');
            Route::put('/{purchaseOrder}', [NewPurchaseOrderController::class, 'update'])->name('update');
            Route::delete('/{purchaseOrder}', [NewPurchaseOrderController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('goods-receipts')->name('goods-receipts.')->group(function () {
            Route::get('/{purchaseOrder}/create', [GoodsReceiptController::class, 'create'])->name('create');
            Route::post('/{purchaseOrder}', [GoodsReceiptController::class, 'store'])->name('store');
        });

        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/{purchaseOrder}/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
            Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('supplier-ledger')->name('supplier-ledger.')->group(function () {
            Route::get('/', [SupplierLedgerController::class, 'index'])->name('index');
            Route::get('/{supplier}', [SupplierLedgerController::class, 'show'])->name('show');
        });
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});
