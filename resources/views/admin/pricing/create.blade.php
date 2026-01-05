<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Pricing Tier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.pricing.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Tier Name
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                Description
                            </label>
                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description"></textarea>
                        </div>

                        <h3 class="text-lg font-bold mb-2">Rates</h3>
                        <div id="rates-container">
                            <div class="flex space-x-4 mb-2">
                                <input type="number" name="rates[0][hour_limit]" placeholder="Hour Limit (e.g. 1)" class="border rounded px-2 py-1" required>
                                <input type="number" step="0.01" name="rates[0][price]" placeholder="Price (RM)" class="border rounded px-2 py-1" required>
                            </div>
                        </div>
                        <button type="button" onclick="addRate()" class="text-sm text-blue-500 mb-4">+ Add Rate</button>

                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Create Tier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rateIndex = 1;
        function addRate() {
            const container = document.getElementById('rates-container');
            const div = document.createElement('div');
            div.className = 'flex space-x-4 mb-2';
            div.innerHTML = `
                <input type="number" name="rates[${rateIndex}][hour_limit]" placeholder="Hour Limit" class="border rounded px-2 py-1" required>
                <input type="number" step="0.01" name="rates[${rateIndex}][price]" placeholder="Price" class="border rounded px-2 py-1" required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500">Remove</button>
            `;
            container.appendChild(div);
            rateIndex++;
        }
    </script>
</x-app-layout>
