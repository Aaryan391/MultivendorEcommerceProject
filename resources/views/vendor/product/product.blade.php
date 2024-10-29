@extends('vendor.navvendor')
@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<style>
    body {
        background-color: grey;
    }
</style>
<div class="container mt-5 mb-4">
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
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Product</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">Product Description</label>
                    <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" required>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="subcategory_id" class="form-label">Subcategory</label>
                    <select class="form-select" id="subcategory_id" name="subcategory_id" required>
                        <option value="">Select a subcategory</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand">
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" id="color" name="color">
                </div>
                <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <input type="text" class="form-control" id="size" name="size">
                </div>
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <input type="text" class="form-control" id="material" name="material">
                </div>
                <div class="mb-3">
                    <label for="style" class="form-label">Style</label>
                    <input type="text" class="form-control" id="style" name="style">
                </div>
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags (comma-separated)</label>
                    <input type="text" class="form-control" id="tags" name="tags">
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="product_image" name="product_image" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</div>

<!-- Display Added Products Card -->
<div class="container mt-5 mb-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Added Products</h5>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ url('storage/' . $product->product_image) }}" class="card-img-top" alt="{{ $product->product_name }}">
                        <div class="card-body">
                            <h5 class="card-title">Name:{{ $product->product_name }}</h5>
                            <p class="card-text">Description:{{ Str::limit($product->product_description, 100) }}</p>
                            <p class="card-text">Price: ${{ number_format($product->price, 2) }}</p>
                            <p class="card-text">Stock: {{ $product->stock }}</p>
                            <p class="card-text">Brand: {{ $product->brand}}</p>
                            <p class="card-text">Color: {{ $product->color }}</p>
                            <p class="card-text">Size: {{ $product->size }}</p>
                            <p class="card-text">Material: {{ $product->material }}</p>
                            <p class="card-text">Style: {{ $product->style }}</p>
                            <p class="card-text">Category: {{ $product->category->name }}</p>
                            <p class="card-text">Subcategory: {{ $product->subcategory->name }}</p>
                            <p class="card-text">
    Tags: {{ is_array($product->tags) ? implode(',', $product->tags) : implode(',', json_decode($product->tags)) }}
</p>

                        </div>
                        <div class="card-footer">
                        @if($product->vendor_id === Auth::id())
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">Edit</button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Edit Product Modal -->
                <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="edit_product_name{{ $product->id }}" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="edit_product_name{{ $product->id }}" name="edit_product_name" value="{{ $product->product_name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_product_description{{ $product->id }}" class="form-label">Product Description</label>
                                        <textarea class="form-control" id="edit_product_description{{ $product->id }}" name="edit_product_description" rows="3" required>{{ $product->product_description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_price{{ $product->id }}" class="form-label">Price</label>
                                        <input type="number" class="form-control" id="edit_price{{ $product->id }}" name="edit_price" value="{{ $product->price }}" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_stock{{ $product->id }}" class="form-label">Stock</label>
                                        <input type="number" class="form-control" id="edit_stock{{ $product->id }}" name="edit_stock" value="{{ $product->stock }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_category_id{{ $product->id }}" class="form-label">Category</label>
                                        <select class="form-select" id="edit_category_id{{ $product->id }}" name="edit_category_id" required>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_subcategory_id{{ $product->id }}" class="form-label">Subcategory</label>
                                        <select class="form-select" id="edit_subcategory_id{{ $product->id }}" name="edit_subcategory_id" required>
                                            @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" @if($subcategory->id == $product->subcategory_id) selected @endif>{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_brand{{ $product->id }}" class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="edit_brand{{ $product->id }}" name="edit_brand" value="{{ $product->brand }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_color{{ $product->id }}" class="form-label">Color</label>
                                        <input type="text" class="form-control" id="edit_color{{ $product->id }}" name="edit_color" value="{{ $product->color }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_size{{ $product->id }}" class="form-label">Size</label>
                                        <input type="text" class="form-control" id="edit_size{{ $product->id }}" name="edit_size" value="{{ $product->size }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_material{{ $product->id }}" class="form-label">Material</label>
                                        <input type="text" class="form-control" id="edit_material{{ $product->id }}" name="edit_material" value="{{ $product->material }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_style{{ $product->id }}" class="form-label">Style</label>
                                        <input type="text" class="form-control" id="edit_style{{ $product->id }}" name="edit_style" value="{{ $product->style }}">
                                    </div>
                                    <div class="mb-3">
    <label for="edit_tags{{ $product->id }}" class="form-label">Tags (comma-separated)</label>
    <input type="text" class="form-control" id="edit_tags{{ $product->id }}" name="edit_tags" value="{{ is_array($product->tags) ? implode(',', $product->tags) : implode(',', json_decode($product->tags)) }}">
</div>
                                    <div class="mb-3">
                                        <label for="edit_product_image{{ $product->id }}" class="form-label">Replace Product Image</label>
                                        <input type="file" class="form-control" id="edit_product_image{{ $product->id }}" name="edit_product_image">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to update subcategories
        function updateSubcategories(categoryId, subcategorySelectId) {
            fetch('/get-subcategories?category_id=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    const subcategorySelect = document.getElementById(subcategorySelectId);
                    subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subcategorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Event listener for the main add product form
        document.getElementById('category_id').addEventListener('change', function() {
            updateSubcategories(this.value, 'subcategory_id');
        });

        // Event listeners for edit product forms
        document.querySelectorAll('[id^="edit_category_id"]').forEach(select => {
            select.addEventListener('change', function() {
                const productId = this.id.replace('edit_category_id', '');
                updateSubcategories(this.value, 'edit_subcategory_id' + productId);
            });
        });
    });
</script>

@endsection