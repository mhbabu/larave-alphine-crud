@extends('layouts.master')

@section('title', 'Product List')

@section('content')
<div class="container mx-auto py-10" x-data="productTable()">
    <div class="flex justify-between mb-6">
        <div class="flex-initial">
            <h1 class="text-2xl font-bold">Product List</h1>
        </div>
        <div class="flex-1 text-right">
            <button @click="openModal" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" title="Create new">
                <i class="fa fa-plus-circle"></i> Create
            </button>
        </div>
    </div>

    <!-- Filter Inputs -->
    <div class="flex justify-between mb-6">
        <input type="text" placeholder="Search..." class="border p-2" x-model="searchQuery" @input.debounce.300ms="filterProducts">
        <select class="border p-2" x-model="sortOrder" @change="filterProducts">
            <option value="">Sort by Price</option>
            <option value="asc">Lowest to Highest</option>
            <option value="desc">Highest to Lowest</option>
        </select>
    </div>

    <table class="min-w-full leading-normal mb-5">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="product in filteredProducts" :key="product.id">
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="product.name"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="product.price"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="product.discount"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="capitalizeFirstLetter(product.status)"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" title="Edit">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    {{ $products->links() }}

    <!-- Modal -->
    <div x-show="showModal" @click.away="closeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Create New Product</h2>
            <form @submit.prevent="createProduct">
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
                <div class="flex justify-end">
                    <button type="button" @click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
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
            createProduct() {
                // Handle product creation logic here (e.g., make an AJAX request)
                this.closeModal();
            },
            filterProducts() {
                const params = new URLSearchParams({
                    searchQuery: this.searchQuery,
                    sortOrder: this.sortOrder
                }).toString();

                fetch(`/products?searchQuery=${this.searchQuery}&sortOrder=${this.sortOrder}`)
    .then(response => response.json())
    .then(data => {
        this.filteredProducts = data.data;
        this.currentPage = data.current_page;
        this.lastPage = data.last_page;
    });
            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        }
    }
</script>
@endsection
