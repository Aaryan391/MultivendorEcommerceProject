@extends('admin.index')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4 text-center display-4">User Management</h1>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <!-- Filter by Role Form -->
            <form action="{{ route('admin.users') }}" method="get" class="mb-4">
                <div class="row g-3 align-items-center justify-content-center">
                    <div class="col-auto">
                        <label for="role" class="form-label fw-bold">Filter by Role:</label>
                    </div>
                    <div class="col-md-4">
                        <select name="role" id="role" class="form-select shadow-sm">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-filter me-2"></i>Apply Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            <td>
                                <!-- Delete Button Form -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
