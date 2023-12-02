@if (Session::has('success'))
    <div class="alert alert-success alert-flights">
        {{ Session::get('success') }}
    </div>
@endif
@if (Session::has('error'))
    <div class="alert alert-danger alert-flights">
        {{ Session::get('error') }}
    </div>
@endif
