@extends('admin.index')
@section('content')
<div class="container">
    <h1>Vendor Requests</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>PAN Number</th>
                <th>Phone Number</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendorRequests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->user->email }}</td>
                    <td>{{ $request->pan_number }}</td>
                    <td>{{ $request->phone_number }}</td>
                    <td>{{ $request->created_at }}</td>
                    <td>
                        <form action="{{ route('admin.acceptVendorRequest', $request->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Accept</button>
                        </form>
                        <form action="{{ route('admin.declineVendorRequest', $request->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Decline</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection