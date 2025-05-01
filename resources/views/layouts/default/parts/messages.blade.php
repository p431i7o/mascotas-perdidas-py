@if(Session::get('success') !== null)
    <div class="container">
        <div class="alert alert-@if(!Session::get('success')){{"warning"}}@else{{"success"}}@endif alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
