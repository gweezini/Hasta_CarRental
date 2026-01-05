@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Voucher Management</h2>
        
        <a href="{{ route('admin.vouchers.create') }}" class="px-4 py-2 bg-[#cb5c55] text-white rounded hover:bg-[#b04b45] transition shadow-sm font-medium flex items-center gap-2">
            <i class="ri-add-line"></i> Create New Voucher
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Single Use</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uses Remaining</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($vouchers as $v)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        {{ $v->code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $v->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $v->label }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $v->single_use ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $v->single_use ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $v->uses_remaining ?? '∞' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $v->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $v->is_active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.vouchers.edit', $v->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        
                        <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this voucher?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-0 cursor-pointer">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($vouchers->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <i class="ri-coupon-3-line text-4xl mb-2 block text-gray-300"></i>
                <p>No vouchers found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
