@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pricing Management</h1>
    </div>

    <div class="row">
        @foreach($tiers as $tier)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ $tier->name }}</div>
                            <div class="mb-2">
                                <small>Vehicles: {{ $tier->vehicles()->count() }}</small>
                            </div>
                            <a href="{{ route('admin.pricing.edit', $tier->id) }}" class="btn btn-sm btn-primary">Edit Prices</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Current Rates:</h6>
                        <ul class="list-unstyled" style="font-size: 0.85rem;">
                            @foreach($tier->rules->sortBy('hour_limit') as $rule)
                                <li>{{ $rule->hour_limit }} Hour(s): RM {{ $rule->price }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
