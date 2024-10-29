@extends('welcome')

@section('content')
<div class="container">
    @auth
    @if(Auth::user()->role == 'user')
    <h1>Request Vendor Role</h1>

    <!-- Flash message for success or error -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('user.requestVendorRole') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pan_number">PAN Number</label>
            <input type="text" class="form-control @error('pan_number') is-invalid @enderror" id="pan_number" name="pan_number" required maxlength="9">
            @error('pan_number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <!-- Phone Number Input Field -->
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" required maxlength="15">
            @error('phone_number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Request Vendor Role</button>
    </form>
    @endif
    @else
    <h1>Please log in to access this page</h1>
    
    @endauth
</div>
@endsection
