<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Details') }}
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

                    <h3 class="text-lg font-semibold mb-4">{{ $product->name }}</h3>

                    <div class="mb-2">
                        <strong>Status:</strong>
                        @if($product->isActive)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Disabled</span>
                        @endif
                    </div>

                    <div class="mb-2">
                        <strong>Price:</strong> R$ {{ number_format($product->price, 2) }}
                    </div>

                    <div class="mb-2">
                        <strong>Amount:</strong> {{ $product->amount }}
                    </div>

                    <div class="mb-2">
                        <strong>Created At:</strong> {{ $product->created_at->format('d/m/Y H:i') }}
                    </div>

                    @if (!$product->isActive)
                        <div class="mb-2">
                            <strong>Deleted At:</strong> {{ $product->deleted_at->format('d/m/Y H:i') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
