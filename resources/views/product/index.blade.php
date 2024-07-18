@extends('layouts.master')

@section('title', 'Product List')

@section('content')
<div class="container mx-auto py-10" x-data="productTable()" x-init="init">
    <div class="flex justify-between mb-6">
        <div class="flex-initial">
            <h1 class="text-2xl font-bold">Product List</h1>
        </div>
        <div class="flex-1 text-right">
            <button @click="openModal"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                title="Create new">
                <i class="fa fa-plus-circle"></i> Create
            </button>
        </div>
    </div>

    <!-- Filter Inputs -->
    <div class="flex justify-between mb-6">
        <input type="text" placeholder="Search by name..." class="border p-2" x-model="searchQuery"
            @input.debounce.300ms="filterProducts">
        <select class="border p-2" x-model="sortOrder" @change="filterProducts">
            <option value="">Sort by Price</option>
            <option value="asc">Lowest to Highest</option>
            <option value="desc">Highest to Lowest</option>
        </select>
    </div>

    <table class="min-w-full leading-normal mb-5">
        <thead>
            <tr>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Name
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Price
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Discount
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Status
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <template x-if="filteredProducts.length === 0">
                <tr>
                    <td colspan="5"
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        No records found...!
                    </td>
                </tr>
            </template>
            <template x-for="product in filteredProducts" :key="product.id">
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        x-text="product.name"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        x-text="product.price"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        x-text="product.discount"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        x-text="capitalizeFirstLetter(product.status)"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <button @click="editProduct(product)"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            title="Edit">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button @click="deleteProduct(product.id)"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    <div class="mt-4">
        <template x-if="lastPage > 1">
            <nav class="flex items-center justify-between">
                <a href="#" class="btn btn-primary" x-on:click.prevent="fetchPage(currentPage - 1)"
                    :disabled="currentPage === 1">Previous</a>
                <div>Page <span x-text="currentPage"></span> of <span x-text="lastPage"></span></div>
                <a href="#" class="btn btn-primary" x-on:click.prevent="fetchPage(currentPage + 1)"
                    :disabled="currentPage === lastPage">Next</a>
            </nav>
        </template>
    </div>

    <!-- Modal -->
    <div x-show="showModal" @click.away="closeModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Create New Product</h2>
            <form @submit.prevent="submitProduct">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
                    <input type="text" class="border p-2 w-full" x-model="newProduct.name" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                    <input type="number" class="border p-2 w-full" x-model="newProduct.price" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Discount:</label>
                    <input type="number" class="border p-2 w-full" x-model="newProduct.discount">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                    <select class="border p-2 w-full" x-model="newProduct.status" required>
                        <option value="publish">Publish</option>
                        <option value="unpublish">Unpublish</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Thumbnail:</label>
                    <input type="file" accept="image/*" class="border p-2 w-full"
                        @change="handleThumbnailUpload" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Images:</label>
                    <input type="file" accept="image/*" multiple class="border p-2 w-full"
                        @change="handleImageUpload">
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="closeModal"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel
                    </button>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
<script>
    function productTable() {
        return {
            products: @json($products->items()),
            filteredProducts: [],
            currentPage: {{ $products->currentPage() }},
            lastPage: {{ $products->lastPage() }},
            searchQuery: '',
            sortOrder: '',
            showModal: false,
            newProduct: {
                name: '',
                price: '',
                discount: '',
                status: 'publish'
            },
            init() {
                this.filteredProducts = this.products;
            },
            openModal() {
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            },
            submitProduct() {
                let formData = new FormData();
                formData.append('name', this.newProduct.name);
                formData.append('price', this.newProduct.price);
                formData.append('discount', this.newProduct.discount);
                formData.append('status', this.newProduct.status);
                formData.append('thumbnail', this.newProduct.thumbnail);
                // Append multiple images if available
                if (this.newProduct.images) {
                    for (let i = 0; i < this.newProduct.images.length; i++) {
                        formData.append('images[]', this.newProduct.images[i]);
                    }
                }

                fetch('/products', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.products.push(data.product);
                    this.closeModal();
                    this.resetForm();
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error creating product:', error);
                });
            },
            filterProducts() {
                const params = new URLSearchParams({
                    searchQuery: this.searchQuery,
                    sortOrder: this.sortOrder
                }).toString();

                fetch(`/filtering-products?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.filteredProducts = data.data;
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            handleThumbnailUpload(event) {
                this.newProduct.thumbnail = event.target.files[0];
            },
            handleImageUpload(event) {
                this.newProduct.images = Array.from(event.target.files);
            },
            resetForm() {
                this.newProduct.name = '';
                this.newProduct.price = '';
                this.newProduct.discount = '';
                this.newProduct.status = 'publish';
                this.newProduct.thumbnail = null;
                this.newProduct.images = null;

                // Reset file inputs
                const fileInputs = document.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    input.value = '';
                });
            },
            deleteProduct(productId) {
                if (confirm('Are you sure you want to delete this product?')) {
                    fetch(`/products/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.products = this.products.filter(product => product.id !== productId);
                        this.filteredProducts = this.filteredProducts.filter(product => product.id !== productId);
                        alert(data.message);
                    })
                    .catch(error => {
                        console.error('Error deleting product:', error);
                    });
                }
            }
        }
    }
</script>
@endsection

