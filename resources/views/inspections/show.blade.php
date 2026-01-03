@if(Auth::user()->role === 'admin' || Auth::user()->role === 'topmanagement')
    @include('inspections.admin_view')
@else
    @include('inspections.customer_view')
@endif
