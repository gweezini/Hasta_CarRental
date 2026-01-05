@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Pricing: {{ $pricing->name }}</h1>
        <a href="{{ route('admin.pricing.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pricing Rules</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pricing.update', $pricing->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-bordered" id="pricingTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Duration (Hours)</th>
                                <th>Price (RM)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pricing->rules->sortBy('hour_limit') as $index => $rule)
                            <tr>
                                <td>
                                    <input type="number" name="rules[{{ $index }}][hour_limit]" class="form-control" value="{{ $rule->hour_limit }}" required>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="rules[{{ $index }}][price]" class="form-control" value="{{ $rule->price }}" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-success btn-sm" id="addRow"><i class="fas fa-plus"></i> Add Rule</button>
                    <button type="submit" class="btn btn-primary btn-sm ml-2">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let ruleIndex = {{ $pricing->rules->count() }};
        
        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.querySelector('#pricingTable tbody');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="number" name="rules[${ruleIndex}][hour_limit]" class="form-control" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="rules[${ruleIndex}][price]" class="form-control" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
            ruleIndex++;
        });

        document.querySelector('#pricingTable').addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endsection
