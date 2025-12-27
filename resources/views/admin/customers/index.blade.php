<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>All Customers</h3>

                    <table class="min-w-full mt-4 text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">ID</th>
                                <th class="border-b p-2">Name</th>
                                <th class="border-b p-2">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr class="hover:bg-gray-100">
                                <td class="border-b p-2">{{ $loop->iteration }}</td>
                                <td class="border-b p-2">{{ $customer->name }}</td>
                                <td class="border-b p-2">{{ $customer->email }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>