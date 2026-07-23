@extends('layouts.modern')

@section('title', 'Production Order Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Production Order Details</h1>
                <p class="text-gray-600 mt-2">{{ $productionOrder->order_no }}</p>
            </div>
            <a href="{{ route('erp.production.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back to Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Order Information --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Order Information</h3>
                <x-heroicon-o-document-text class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Order No</p>
                    <p class="font-medium text-gray-900">{{ $productionOrder->order_no }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Product</p>
                    <p class="font-medium text-gray-900">{{ $productionOrder->product->product_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantity to Produce</p>
                    <p class="font-medium text-gray-900">{{ number_format($productionOrder->quantity_to_produce, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Production Date</p>
                    <p class="font-medium text-gray-900">{{ $productionOrder->production_date }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <x-ui.badge :variant="$productionOrder->status === 'completed' ? 'success' : ($productionOrder->status === 'in_progress' ? 'warning' : 'secondary')">
                        {{ ucfirst(str_replace('_', ' ', $productionOrder->status)) }}
                    </x-ui.badge>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Remarks</p>
                    <p class="font-medium text-gray-900">{{ $productionOrder->remarks ?? '—' }}</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Formula Requirements --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Formula Requirements</h3>
                <x-heroicon-o-cube class="h-5 w-5 text-gray-400" />
            </div>
            @if ($productionOrder->formula)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Required</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productionOrder->formula->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->rawMaterial->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($item->quantity_required * $productionOrder->quantity_to_produce, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->unit ?? $item->rawMaterial->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No formula assigned.</p>
                </div>
            @endif
        </x-ui.card>
    </div>

    {{-- Worker Assignment --}}
    <x-ui.card class="mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Worker Assignment</h3>
            <x-heroicon-o-users class="h-5 w-5 text-gray-400" />
        </div>
        <form method="POST" action="{{ route('erp.production.orders.assign-workers', $productionOrder) }}">
            @csrf
            <div id="workers-container" class="space-y-4">
                @foreach ($productionOrder->workers as $index => $worker)
                    <div class="worker-row grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                            <select name="workers[{{ $index }}][employee_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                                <option value="">Select employee</option>
                                @foreach (\App\Models\Employee::where('status', 'active')->get() as $employee)
                                    <option value="{{ $employee->id }}" {{ $worker->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Work</label>
                            <input type="text" name="workers[{{ $index }}][assigned_work]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $worker->assigned_work }}">
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Completed Quantity</label>
                            <input type="number" step="0.01" name="workers[{{ $index }}][completed_quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $worker->completed_quantity }}">
                        </div>
                        <div class="col-span-1">
                            <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-worker">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200" id="add-worker">
                <x-heroicon-o-plus class="h-5 w-5" />
                Add Worker
            </button>
            <button type="submit" class="mt-4 ml-4 inline-flex items-center gap-2 px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-check class="h-5 w-5" />
                Save Workers
            </button>
        </form>
    </x-ui.card>

    {{-- Actions --}}
    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
            <x-heroicon-o-bolt class="h-5 w-5 text-gray-400" />
        </div>
        <div class="flex items-center gap-4">
            @if ($productionOrder->status === 'pending')
                <form action="{{ route('erp.production.orders.status', $productionOrder) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="in_progress">
                    <button class="inline-flex items-center gap-2 px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-all duration-200" type="submit">
                        <x-heroicon-o-play class="h-5 w-5" />
                        Start Production
                    </button>
                </form>
            @elseif ($productionOrder->status === 'in_progress')
                <form action="{{ route('erp.production.orders.status', $productionOrder) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-all duration-200" type="submit">
                        <x-heroicon-o-check-circle class="h-5 w-5" />
                        Complete Production
                    </button>
                </form>
            @endif
        </div>
    </x-ui.card>
    
    <script>
        let workerCount = {{ $productionOrder->workers->count() }};
        
        document.getElementById('add-worker').addEventListener('click', function() {
            const container = document.getElementById('workers-container');
            const newRow = document.createElement('div');
            newRow.className = 'worker-row grid grid-cols-12 gap-4 items-end';
            newRow.innerHTML = `
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                    <select name="workers[${workerCount}][employee_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        <option value="">Select employee</option>
                        @foreach (\App\Models\Employee::where('status', 'active')->get() as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Work</label>
                    <input type="text" name="workers[${workerCount}][assigned_work]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Completed Quantity</label>
                    <input type="number" step="0.01" name="workers[${workerCount}][completed_quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="0">
                </div>
                <div class="col-span-1">
                    <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-worker">
                        <x-heroicon-o-trash class="h-5 w-5" />
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            workerCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-worker') || e.target.closest('.remove-worker')) {
                e.target.closest('.worker-row').remove();
            }
        });
    </script>
@endsection
