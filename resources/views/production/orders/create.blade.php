@extends('layouts.modern')

@section('title', 'Create Production Order')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('erp.production.orders.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="h-6 w-6" />
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Production Order</h1>
                <p class="text-gray-600 mt-2">Create a new production order for manufacturing</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl">
        @if(session('error'))
            <x-ui.alert variant="danger">
                {{ session('error') }}
                @if(session('stock_issues'))
                    <ul class="mt-2 list-disc list-inside">
                        @foreach(session('stock_issues') as $issue)
                            <li>{{ $issue['material'] }}: Required {{ number_format($issue['required'], 2) }}, Available {{ number_format($issue['available'], 2) }}, Shortage {{ number_format($issue['shortage'], 2) }}</li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.alert>
        @endif

        <x-ui.card>
            <form method="POST" action="{{ route('erp.production.orders.store') }}">
                @csrf
                
                {{-- Basic Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-cube class="h-5 w-5 text-blue-600" />
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product <span class="text-red-500">*</span></label>
                            <select name="product_id" required id="product-select" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-formula="{{ $product->productionFormula ? 'yes' : 'no' }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                            <p class="text-sm mt-1" id="formula-status"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Produce <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="quantity_to_produce" required id="quantity-input" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Production Date <span class="text-red-500">*</span></label>
                            <input type="date" name="production_date" value="{{ now()->format('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                        <textarea name="remarks" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                
                {{-- Formula Preview --}}
                <div id="formula-preview" class="mb-8 hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-blue-600" />
                        Formula Preview
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Required per Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Available Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Required</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody id="formula-items" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('erp.production.orders.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <x-heroicon-o-check class="h-5 w-5" />
                        Create Order
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
    
    <script>
        const formulas = @json($products->map(fn($p) => ['id' => $p->id, 'formula' => $p->productionFormula?->load('items.rawMaterial')]));
        
        document.getElementById('product-select').addEventListener('change', function() {
            const productId = this.value;
            const formulaData = formulas.find(f => f.id == productId);
            const formulaPreview = document.getElementById('formula-preview');
            const formulaStatus = document.getElementById('formula-status');
            const formulaItems = document.getElementById('formula-items');
            
            if (formulaData && formulaData.formula) {
                formulaStatus.textContent = 'Formula available';
                formulaStatus.className = 'text-sm text-emerald-600';
                formulaPreview.classList.remove('hidden');
                
                formulaItems.innerHTML = '';
                formulaData.formula.items.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 transition-colors';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.rawMaterial.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.quantity_required} ${item.unit || item.rawMaterial.unit}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.rawMaterial.current_stock} ${item.rawMaterial.unit}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" id="required-${item.rawMaterial.id}">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm" id="status-${item.rawMaterial.id}">-</td>
                    `;
                    formulaItems.appendChild(row);
                });
            } else {
                formulaStatus.textContent = 'No formula available for this product';
                formulaStatus.className = 'text-sm text-red-600';
                formulaPreview.classList.add('hidden');
            }
        });
        
        document.getElementById('quantity-input').addEventListener('input', function() {
            const quantity = parseFloat(this.value) || 0;
            const productId = document.getElementById('product-select').value;
            const formulaData = formulas.find(f => f.id == productId);
            
            if (formulaData && formulaData.formula) {
                formulaData.formula.items.forEach(item => {
                    const required = item.quantity_required * quantity;
                    const available = item.rawMaterial.current_stock;
                    const requiredCell = document.getElementById(`required-${item.rawMaterial.id}`);
                    const statusCell = document.getElementById(`status-${item.rawMaterial.id}`);
                    
                    if (requiredCell && statusCell) {
                        requiredCell.textContent = `${required.toFixed(2)} ${item.unit || item.rawMaterial.unit}`;
                        
                        if (available >= required) {
                            statusCell.textContent = 'Sufficient';
                            statusCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-emerald-600 font-medium';
                        } else {
                            statusCell.textContent = `Insufficient (Shortage: ${(required - available).toFixed(2)})`;
                            statusCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium';
                        }
                    }
                });
            }
        });
    </script>
@endsection
