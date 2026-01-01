@extends('layouts.app')

@section('content')
<div class="section__container">
    <h2 class="section__header">Voucher Templates</h2>
    @if(session('success'))<div style="color:green">{{ session('success') }}</div>@endif
    <a href="{{ route('admin.vouchers.create') }}" class="btn">Create New Voucher</a>

    <table style="width:100%; margin-top:1rem; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align:left; padding:8px;">Code</th>
                <th style="text-align:left; padding:8px;">Name</th>
                <th style="text-align:left; padding:8px;">Reward</th>
                <th style="text-align:left; padding:8px;">Single Use</th>
                <th style="text-align:left; padding:8px;">Uses Remaining</th>
                <th style="text-align:left; padding:8px;">Active</th>
                <th style="text-align:left; padding:8px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vouchers as $v)
            <tr style="border-top:1px solid #eee">
                <td style="padding:8px">{{ $v->code }}</td>
                <td style="padding:8px">{{ $v->name }}</td>
                <td style="padding:8px">{{ $v->label }}</td>
                <td style="padding:8px">{{ $v->single_use ? 'Yes' : 'No' }}</td>
                <td style="padding:8px">{{ $v->uses_remaining ?? '-' }}</td>
                <td style="padding:8px">{{ $v->is_active ? 'Yes' : 'No' }}</td>
                <td style="padding:8px">
                    <a href="{{ route('admin.vouchers.edit', $v->id) }}">Edit</a>
                    <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" style="display:inline-block; margin-left:8px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:#d14a1e; cursor:pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
