@extends('layouts.admin')

@section('header_title', 'Staff Payroll Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Staff Payroll</h2>
            <p class="text-gray-500 text-sm mt-1">Manage and audit staff salaries and banking information</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition shadow-sm no-print">
                <i class="ri-printer-line"></i> Print Payroll
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl">
                    <i class="ri-group-line"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Staff</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $staffs->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-[#cb5c55] p-6 rounded-3xl shadow-lg border border-[#cb5c55]/20 text-white">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center text-2xl">
                    <i class="ri-money-dollar-box-line"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-white/70 uppercase tracking-widest">Total Monthly Payroll</p>
                    <h3 class="text-2xl font-black text-white">RM {{ number_format($staffs->sum('salary'), 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-2xl">
                    <i class="ri-shield-check-line"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Payroll Status</p>
                    <h3 class="text-2xl font-black text-green-600 uppercase">Active</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Staff Info</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Position</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Banking Details</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Salary (RM)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($staffs as $staff)
                    <tr class="hover:bg-gray-50/80 transition duration-200 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center font-bold text-sm border-2 border-white shadow-sm overflow-hidden">
                                    @if($staff->profile_photo_path)
                                        <img src="{{ $staff->profile_photo_url }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($staff->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-[#cb5c55] transition">{{ $staff->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono tracking-tighter">{{ $staff->matric_staff_id ?? 'NO ID SET' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $staff->role == 'topmanagement' ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                {{ $staff->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-700 uppercase">{{ $staff->bank_name ?? 'Not Provided' }}</span>
                                <span class="text-xs text-gray-400 font-mono">{{ $staff->account_number ?? '---' }}</span>
                                @if($staff->account_holder)
                                <span class="text-[9px] text-gray-400 mt-1 italic">Holder: {{ $staff->account_holder }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-lg font-black text-gray-800">
                                RM {{ number_format($staff->salary, 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400">
                            <i class="ri-user-search-line text-5xl mb-3 block opacity-20"></i>
                            <p>No staff records found in the database.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50/50 border-t-2 border-gray-100">
                    <tr>
                        <td colspan="3" class="px-8 py-6 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Total Payroll Amount</td>
                        <td class="px-8 py-6 text-right font-black text-2xl text-[#cb5c55]">
                            RM {{ number_format($staffs->sum('salary'), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="mt-8 p-6 bg-blue-50 border border-blue-100 rounded-3xl flex items-start gap-4 no-print">
        <i class="ri-information-line text-blue-500 text-xl"></i>
        <div class="text-xs text-blue-700 leading-relaxed">
            <p class="font-bold mb-1 uppercase tracking-tight">Financial Policy Note:</p>
            <p>This payroll summary is only accessible by Top Management. Salaries are automatically deducted from the Gross Revenue in the Financial Reports to calculate Net Profit. Ensure all staff members update their banking details in their respective profiles.</p>
        </div>
    </div>
</div>
@endsection