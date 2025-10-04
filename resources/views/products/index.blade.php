<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- MENSAGENS DE ERRO --}}
                    @if($errors->any())
                        <div class="mb-2 text-sm text-red-500 dark:text-red-400">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- MENSAGENS DE SUCESSO --}}
                    @if(session('success'))
                        <div class="mb-4 text-sm text-green-500 dark:text-green-400">
                            @foreach((array) session('success') as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- BOTÃO CRIAR NOVO PRODUTO --}}
                    <div class="flex justify-start mb-4">
                        <a href="{{ route('products.viewCreate') }}"
                        class="inline-flex items-center px-5 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md 
                                hover:bg-green-700 hover:shadow-lg transition duration-300 transform hover:-translate-y-1 
                                dark:bg-green-500 dark:hover:bg-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create New Product
                        </a>
                    </div>

                    {{-- FILTROS --}}
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <form method="GET" class="flex flex-wrap gap-2 sm:gap-4 w-full">
                            {{-- Filtro por Nome --}}
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Search by name"
                                class="px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-400">

                            {{-- Filtro por Status --}}
                            <select name="status"
                                class="px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-400">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Disabled</option>
                            </select>

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                Apply
                            </button>

                        </form>
                    </div>

                    {{-- TABELA DE PRODUTOS --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        Name
                                    </th>
                                    {{-- Coluna Status clicável para ordenação --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'status_asc' ? 'status_desc' : 'status_asc']) }}">
                                            Status
                                            @if(request('sort') === 'status_asc') ↑
                                            @elseif(request('sort') === 'status_desc') ↓
                                            @endif
                                        </a>
                                    </th>
                                    {{-- Coluna Price clicável para ordenação --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'price_asc' ? 'price_desc' : 'price_asc']) }}">
                                            Price
                                            @if(request('sort') === 'price_asc') ↑
                                            @elseif(request('sort') === 'price_desc') ↓
                                            @endif
                                        </a>
                                    </th>
                                    {{-- Coluna Amount clicável para ordenação --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'amount_asc' ? 'amount_desc' : 'amount_asc']) }}">
                                            Amount
                                            @if(request('sort') === 'amount_asc') ↑
                                            @elseif(request('sort') === 'amount_desc') ↓
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'created_at_asc' ? 'created_at_desc' : 'created_at_asc']) }}">
                                            Created At
                                            @if(request('sort') === 'created_at_asc') ↑
                                            @elseif(request('sort') === 'created_at_desc') ↓
                                            @endif
                                        </a>
                                    </th>
                                    {{-- Coluna Ações --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->isActive)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Disabled</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($product->price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                        {{-- Ação de visualizar detalhes --}}
                                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                            {{-- Visualizar --}}
                                            <a href="{{ route('products.show', ['id' => $product->id]) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200" title="View">👁️</a>
                                            {{-- Editar --}}
                                            <a href="{{ route('products.viewEdit', ['id' => $product->id]) }}" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200" title="Edit">✏️</a>
                                            {{-- Excluir --}}
                                            <form action="{{ route('products.destroy', ['id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200" title="Delete">🗑️</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No products found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINAÇÃO --}}
                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
