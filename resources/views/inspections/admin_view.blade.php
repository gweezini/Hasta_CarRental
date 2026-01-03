@extends('layouts.admin')

@section('header_title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <i class="ri-arrow-left-line"></i>
        </a>
        Inspection Details
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        @include('inspections.partials.report_card')
    </div>
@endsection
