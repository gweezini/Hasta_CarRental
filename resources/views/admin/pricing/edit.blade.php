@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.pricing.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
            <i class="ri-arrow-left-line mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Pricing: <span class="text-[#cb5c55]">{{ $pricing->name }}</span></h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Pricing Rules</h3>
            <p class="text-sm text-gray-500">Define the price for each duration.</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.pricing.update', $pricing->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="pricingTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Duration (Hours)
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Price (RM)
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="pricingTableBody">
                            @foreach($pricing->rules->sortBy('hour_limit') as $index => $rule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="number" name="rules[{{ $index }}][hour_limit]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" value="{{ $rule->hour_limit }}" required>
                                        <span class="ml-2 text-sm text-gray-500">Hrs</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">RM</span>
                                        </div>
                                        <input type="number" step="0.01" name="rules[{{ $index }}][price]" class="block w-full rounded-md border-gray-300 pl-10 focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" value="{{ $rule->price }}" required>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" class="text-red-500 hover:text-red-700 remove-row transition">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <button type="button" id="addRow" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-green-100 active:bg-green-200 focus:outline-none focus:border-green-300 focus:ring ring-green-200 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="ri-add-line mr-2 text-lg"></i> Add Rule
                    </button>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-[#cb5c55] border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-[#b04a44] active:bg-[#963f3a] focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find highest index to avoid collisions
        let ruleIndex = {{ $pricing->rules->count() > 0 ? $pricing->rules->max(fn($r) => $r->id) + 100 : 0 }}; 
        // Using a safe larger number logic or just increment from count if simple
        // Resetting simple counter for this page context:
        let currentIndex = {{ $pricing->rules->count() }};
        
        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.getElementById('pricingTableBody');
            const tr = document.createElement('tr');
            
            // Generate a unique index based on timestamp to be safe or just increment
            currentIndex++; 
            
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="number" name="rules[new_${currentIndex}][hour_limit]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" required>
                        <span class="ml-2 text-sm text-gray-500">Hrs</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">RM</span>
                        </div>
                        <input type="number" step="0.01" name="rules[new_${currentIndex}][price]" class="block w-full rounded-md border-gray-300 pl-10 focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" required>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" class="text-red-500 hover:text-red-700 remove-row transition">
                        <i class="ri-delete-bin-line text-lg"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('pricingTable').addEventListener('click', function(e) {
            // Traverse up to find the button if icon is clicked
            const btn = e.target.closest('.remove-row');
            if (btn) {
                btn.closest('tr').remove();
            }
        });
    });
</script>
@endsection
