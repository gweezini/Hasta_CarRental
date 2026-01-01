@extends('layouts.app')

@section('content')
<div class="section__container">
    <h2 class="section__header">Edit Voucher</h2>

    @if(session('success'))<div style="color:green">{{ session('success') }}</div>@endif
    @if(session('error'))<div style="color:red">{{ session('error') }}</div>@endif

    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Code</label>
            <input type="text" name="code" value="{{ $voucher->code }}" required />
        </div>
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{ $voucher->name }}" required />
        </div>
        <div>
            <label>Type</label>
            <select name="type">
                <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Fixed (RM off)</option>
                <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>Percent (%)</option>
            </select>
        </div>
        <div>
            <label>Value</label>
            <input type="number" step="0.01" name="value" value="{{ $voucher->value }}" required />
        </div>
        <div>
            <label>Single Use</label>
            <input type="checkbox" name="single_use" {{ $voucher->single_use ? 'checked' : '' }} />
        </div>
        <div>
            <label>Uses Remaining (nullable)</label>
            <input type="number" name="uses_remaining" value="{{ $voucher->uses_remaining }}" />
        </div>
        <div>
            <label>Active</label>
            <input type="checkbox" name="is_active" {{ $voucher->is_active ? 'checked' : '' }} />
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
