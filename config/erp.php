<?php

return [

    'name' => env('APP_NAME', 'BikeSeat ERP'),

    'footer' => 'Motorcycle Seat Manufacturing ERP',

    /*
    |--------------------------------------------------------------------------
    | Sidebar Navigation
    |--------------------------------------------------------------------------
    */
    'menu' => [
        [
            'title' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'dashboard',
        ],
        [
            'title' => 'User Management',
            'icon' => 'fas fa-users-cog',
            'active' => 'user-management.*',
            'children' => [
                ['title' => 'Users', 'route' => 'user-management.users.index', 'icon' => 'fas fa-user'],
                ['title' => 'Roles', 'route' => 'user-management.roles.index', 'icon' => 'fas fa-user-tag'],
                ['title' => 'Permissions', 'route' => 'user-management.permissions.index', 'icon' => 'fas fa-key'],
            ],
        ],
        [
            'title' => 'Employees',
            'icon' => 'fas fa-id-badge',
            'active' => 'employees.*',
            'children' => [
                ['title' => 'Employees', 'route' => 'erp.employees.index', 'icon' => 'fas fa-users'],
                ['title' => 'Departments', 'route' => 'erp.employees.departments.index', 'icon' => 'fas fa-building'],
                ['title' => 'Attendance', 'route' => 'erp.employees.attendance.index', 'icon' => 'fas fa-calendar-check'],
            ],
        ],
        [
            'title' => 'Supplier Management',
            'icon' => 'fas fa-truck-loading',
            'active' => 'suppliers.*',
            'children' => [
                ['title' => 'Suppliers', 'route' => 'erp.suppliers.index', 'icon' => 'fas fa-industry'],
                ['title' => 'Purchase History', 'route' => 'erp.suppliers.purchase-history.index', 'icon' => 'fas fa-history'],
            ],
        ],
        [
            'title' => 'Purchase Management',
            'icon' => 'fas fa-shopping-cart',
            'active' => 'purchases.*',
            'children' => [
                ['title' => 'Purchase Orders', 'route' => 'purchases.purchase-orders.index', 'icon' => 'fas fa-file-invoice'],
                ['title' => 'Payments', 'route' => 'purchases.payments.index', 'icon' => 'fas fa-money-bill'],
                ['title' => 'Supplier Ledger', 'route' => 'purchases.supplier-ledger.index', 'icon' => 'fas fa-book'],
            ],
        ],
        [
            'title' => 'Raw Material',
            'icon' => 'fas fa-boxes',
            'active' => 'raw-materials.*',
            'children' => [
                ['title' => 'Raw Materials', 'route' => 'erp.raw-materials.index', 'icon' => 'fas fa-cubes'],
                ['title' => 'Categories', 'route' => 'erp.raw-materials.categories.index', 'icon' => 'fas fa-tags'],
                ['title' => 'Stock Ledger', 'route' => 'erp.raw-materials.stock-ledger.index', 'icon' => 'fas fa-book'],
                ['title' => 'Stock Adjustments', 'route' => 'erp.raw-materials.stock-adjustments.index', 'icon' => 'fas fa-sliders-h'],
            ],
        ],
        [
            'title' => 'Production',
            'icon' => 'fas fa-industry',
            'active' => 'production.*',
            'children' => [
                ['title' => 'Production Orders', 'route' => 'erp.production.orders.index', 'icon' => 'fas fa-clipboard-list'],
                ['title' => 'Workers', 'route' => 'erp.production.workers.index', 'icon' => 'fas fa-hard-hat'],
                ['title' => 'Daily Production', 'route' => 'erp.production.daily.index', 'icon' => 'fas fa-calendar-day'],
                ['title' => 'Production Formula', 'route' => 'erp.production.formula.index', 'icon' => 'fas fa-flask'],
                ['title' => 'Material Consumption', 'route' => 'erp.production.material-consumption.index', 'icon' => 'fas fa-weight'],
            ],
        ],
        [
            'title' => 'Inventory',
            'icon' => 'fas fa-warehouse',
            'active' => 'inventory.*',
            'children' => [
                ['title' => 'Finished Goods', 'route' => 'erp.inventory.finished-goods.index', 'icon' => 'fas fa-chair'],
                ['title' => 'Stock', 'route' => 'erp.inventory.stock.index', 'icon' => 'fas fa-box-open'],
                ['title' => 'Stock Transactions', 'route' => 'erp.inventory.transactions.index', 'icon' => 'fas fa-exchange-alt'],
            ],
        ],
        [
            'title' => 'Customers',
            'icon' => 'fas fa-user-friends',
            'active' => 'customers.*',
            'children' => [
                ['title' => 'Customers', 'route' => 'erp.customers.index', 'icon' => 'fas fa-address-book'],
                ['title' => 'Customer Orders', 'route' => 'erp.customers.orders.index', 'icon' => 'fas fa-shopping-cart'],
                ['title' => 'Delivery Records', 'route' => 'erp.customers.delivery-records.index', 'icon' => 'fas fa-shipping-fast'],
            ],
        ],
        [
            'title' => 'Reports',
            'icon' => 'fas fa-chart-bar',
            'route' => 'reports.index',
        ],
        [
            'title' => 'Settings',
            'icon' => 'fas fa-cog',
            'route' => 'settings.index',
        ],
    ],

];
