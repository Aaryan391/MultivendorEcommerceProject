@extends('vendor.navvendor')
@section('content')
<style>
    body {
        background-color: #495057;
    }
</style>
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Add Category Form Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Add Category</h5>
                    <form action="{{ route('storeCategory') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name:</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Add Subcategory Form Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Add Subcategory</h5>
                    <form action="{{ route('storeSubcategory') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="subcategory_name" class="form-label">Subcategory Name:</label>
                            <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Select Category:</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Add Subcategory</button>
                    </form>
                </div>
            </div>

            <!-- Display Categories and Subcategories -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Categories and Subcategories</h5>

                    <h6 class="card-subtitle mb-2 text-muted">Categories:</h6>
                    <ul class="list-group mb-4">
                        @foreach($categories as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $category->name }}
                            @if($category->vendor_id === Auth::id())
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">Edit</button>
                                <form action="{{ route('deleteCategory', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-2">Delete</button>
                                </form>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>

                    <h6 class="card-subtitle mb-2 text-muted">Subcategories:</h6>
                    @foreach($categories as $category)
                        <div class="mb-3">
                            <strong>{{ $category->name }}</strong>:
                            <ul class="list-group">
                                @foreach($category->subcategories as $subcategory)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $subcategory->name }}
                                        @if($subcategory->vendor_id === Auth::id())
                                        <div>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal{{ $subcategory->id }}">Edit</button>
                                            <form action="{{ route('deleteSubcategory', $subcategory->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ms-2">Delete</button>
                                            </form>
                                        </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Modals for Editing Category and Subcategory -->
            @foreach($categories as $category)
                <!-- Edit Category Modal -->
                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('updateCategory', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="edit_category_name" class="form-label">Category Name:</label>
                                        <input type="text" class="form-control" id="edit_category_name" name="edit_category_name" value="{{ $category->name }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Subcategory Modals -->
                @foreach($category->subcategories as $subcategory)
                    <div class="modal fade" id="editSubcategoryModal{{ $subcategory->id }}" tabindex="-1" aria-labelledby="editSubcategoryModalLabel{{ $subcategory->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSubcategoryModalLabel{{ $subcategory->id }}">Edit Subcategory</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('updateSubcategory', $subcategory->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="edit_subcategory_name" class="form-label">Subcategory Name:</label>
                                            <input type="text" class="form-control" id="edit_subcategory_name" name="edit_subcategory_name" value="{{ $subcategory->name }}" required>
                                        </div>
                                        <input type="hidden" name="subcategory_id" value="{{ $subcategory->id }}">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endsection