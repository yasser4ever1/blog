@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Success!</h4>

        <hr>

         {{ Session::get('success') }}
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error!</h4>

        <hr>

         {{ Session::get('error') }}
    </div>
@endif

@if(count($errors) > 0)
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error!</h4>

        <hr>
        
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="alert message" role="alert" style="display: none;"></div>