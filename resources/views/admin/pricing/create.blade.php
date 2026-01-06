@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.pricing.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
            <i class="ri-arrow-left-line mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Create Pricing Tier</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">New Tier Details</h3>
            <p class="text-sm text-gray-500">Create a new pricing tier and define its rules.</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.pricing.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tier Name</label>
                        <input type="text" name="name" id="name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50" required placeholder="e.g. Axia Tier 3">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                        <textarea name="description" id="description" rows="1" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50" placeholder="Brief description..."></textarea>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-md font-bold text-gray-700 mb-4">Pricing Rates</h4>
                    
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                                </th>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="pricingTableBody">
                                <!-- Initial Row -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <input type="number" name="rates[0][hour_limit]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" placeholder="1" required>
                                            <span class="ml-2 text-sm text-gray-500">Hrs</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-gray-500 sm:text-sm">RM</span>
                                            </div>
                                            <input type="number" step="0.01" name="rates[0][price]" class="block w-full rounded-md border-gray-300 pl-10 focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" placeholder="0.00" required>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button" class="text-red-500 hover:text-red-700 remove-row transition" disabled opacity-50>
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <button type="button" id="addRate" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-green-100 active:bg-green-200 focus:outline-none focus:border-green-300 focus:ring ring-green-200 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="ri-add-line mr-2 text-lg"></i> Add RateRow
                        </button>
                        
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-[#cb5c55] border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-[#b04a44] active:bg-[#963f3a] focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                            Create Tier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rateIndex = 1;

        document.getElementById('addRate').addEventListener('click', function() {
            const tbody = document.getElementById('pricingTableBody');
            const tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="number" name="rates[${rateIndex}][hour_limit]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" required>
                        <span class="ml-2 text-sm text-gray-500">Hrs</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">RM</span>
                        </div>
                        <input type="number" step="0.01" name="rates[${rateIndex}][price]" class="block w-full rounded-md border-gray-300 pl-10 focus:border-[#cb5c55] focus:ring focus:ring-[#cb5c55] focus:ring-opacity-50 text-sm" required>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" class="text-red-500 hover:text-red-700 remove-row transition">
                        <i class="ri-delete-bin-line text-lg"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            rateIndex++;
        });

        document.getElementById('pricingTable').addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-row');
            if (btn) {
                // Optional: Prevent deleting the last row if needed, but for now allowing removal
                btn.closest('tr').remove();
            }
        });
    });
</script>
@endsection
