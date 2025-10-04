<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Mensagens de erro --}}
                @if($errors->any())
                    <div class="mb-4 text-sm text-red-500 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('products.create') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-500 dark:focus:ring-green-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                        <input type="number" id="price" name="price" step="0.01" required value="{{ old('price') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-500 dark:focus:ring-green-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                        <input type="number" id="amount" name="amount" required value="{{ old('amount') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-500 dark:focus:ring-green-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md 
                                   hover:bg-green-700 hover:shadow-lg transition duration-300 transform hover:-translate-y-0.5 dark:bg-green-500 dark:hover:bg-green-600">
                            Create Product
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
