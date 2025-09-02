<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- MENSAGENS DE SUCESSO --}}
                    @if(session('success'))
                        <div class="mb-4 text-sm text-green-500 dark:text-green-400">
                            @foreach((array) session('success') as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- MENSAGENS DE ERRO --}}
                    @if($errors->any())
                        <div class="mb-4 text-sm text-red-500 dark:text-red-400">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- FORMULÁRIO DE EDIÇÃO --}}
                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nome --}}
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-400">
                        </div>

                        {{-- Preço --}}
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-400">
                        </div>

                        {{-- Quantidade --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $product->amount) }}"
                                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-400">
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-400">
                                <option value="1" {{ old('status', $product->isActive) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('status', $product->isActive) ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>

                        {{-- BOTÕES --}}
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('products.index') }}"
                               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                               Cancel
                            </a>
                            <button type="submit"
                               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                               Save
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                    