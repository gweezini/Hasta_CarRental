@extends('layouts.admin')

@section('header_title', 'Customer Management')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Customer List</h2>
        <p class="text-gray-500 text-sm">Manage all registered students and staff</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Registered Customers</h3>
            <span class="bg-[#cb5c55] text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                Total: {{ $customers->count() }}
            </span>
        </div>
        
        <div class="overflow-x-auto text-left">
            <table class="w-full text-sm text-gray-600">
                <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Matric / Staff ID</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">College</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($customers as $customer)
                    <tr x-data="{ showBlacklistModal: false }" class="hover:bg-gray-50 transition {{ $customer->is_blacklisted ? 'bg-red-50/20' : '' }}">
                        <td class="px-6 py-4 font-bold text-gray-400 text-xs">#{{ $customer->id }}</td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500 border-2 border-white shadow-sm">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="font-bold text-gray-800 hover:text-[#cb5c55] hover:underline transition block">
                                        {{ $customer->name }}
                                    </a>
                                    
                                    @if($customer->is_blacklisted)
                                        <p class="text-[10px] text-red-600 font-bold mt-0.5 bg-red-100 px-1.5 py-0.5 rounded w-fit">
                                            Reason: {{ $customer->blacklist_reason ?? 'No reason' }}
                                        </p>
                                    @else
                                        <span class="text-[10px] text-gray-400">Joined {{ $customer->created_at->format('M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded border">
                                {{ $customer->matric_staff_id ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-[10px] text-gray-400 font-mono mt-0.5">
                                {{ $customer->phone_number ?? $customer->phone ?? 'No Phone' }}
                            </p>
                            <p class="text-xs font-bold text-gray-700 mt-0.5">{{ $customer->email }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-600 font-medium">{{ $customer->college ? $customer->college->name : '-' }}</p>
                        </td>

                        <td class="px-6 py-4 uppercase tracking-tighter">
                            @if($customer->is_blacklisted)
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 block w-fit text-center">
                                    BLACKLISTED
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 block w-fit text-center">
                                    ACTIVE
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-[10px] font-bold uppercase tracking-wider hover:bg-gray-200 hover:text-gray-800 transition">
                                    View
                                </a>

                                @if($customer->is_blacklisted)
                                    <form action="{{ route('admin.customers.blacklist', $customer->id) }}" method="POST" onsubmit="return confirm('Confirm to whitelist this user?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-600 rounded-md text-[10px] font-bold uppercase tracking-wider hover:bg-green-100 hover:text-green-800 transition">
                                            Whitelist
                                        </button>
                                    </form>
                                @else
                                    <button @click="showBlacklistModal = true" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-md text-[10px] font-bold uppercase tracking-wider hover:bg-red-100 hover:text-red-800 transition">
                                        Blacklist
                                    </button>
                                @endif
                            </div>

                            <div x-show="showBlacklistModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                                <div @click.away="showBlacklistModal = false" class="bg-white rounded-xl shadow-2xl w-96 p-6 text-left transform transition-all scale-100 border border-gray-100">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">Blacklist User</h3>
                                    <p class="text-xs text-gray-500 mb-4">Please provide a reason for blacklisting <strong>{{ $customer->name }}</strong>.</p>
                                    
                                    <form action="{{ route('admin.customers.blacklist', $customer->id) }}" method="POST">
                                        @csrf
                                        <textarea name="blacklist_reason" rows="3" required placeholder="Reason (e.g. Damaged vehicle...)" class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-4 p-3 bg-gray-50 border outline-none resize-none"></textarea>
                                        
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showBlacklistModal = false" class="px-4 py-2 text-xs font-bold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                                            <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-red-500 rounded-lg hover:bg-red-600 transition shadow-lg">
                                                Confirm
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100">
            @if(method_exists($customers, 'links'))
                {{ $customers->links() }}
            @endif
        </div>
    </div>
@endsection