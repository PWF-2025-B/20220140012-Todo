<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Todo') }}
        </h2>
    </x-slot>

    <div class="w-full bg-gray-900 overflow-hidden shadow-sm">
        <div class="w-full p-6 text-xl text-white">
            <div class="flex items-center justify-between mb-4">
                <x-create-button href="{{ route('todo.create') }}" />

                <div>
                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="text-sm text-green-400">
                            {{ session('success') }}
                        </p>
                    @endif
                    @if (session('danger'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="text-sm text-red-400">
                            {{ session('danger') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-900 text-white">
                        @forelse ($todos as $todo)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $todo->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $todo->is_complete ? 'Completed' : 'On Going' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('todo.edit', $todo) }}"
                                           class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded">
                                            Edit
                                        </a>

                                        @if (!$todo->is_complete)
                                            <form action="{{ route('todo.complete', $todo) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-xs font-semibold text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded">
                                                    Completed
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('todo.uncomplete', $todo) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-xs font-semibold text-white bg-yellow-600 hover:bg-yellow-700 px-3 py-1 rounded">
                                                    Uncomplete
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('todo.destroy', $todo) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs font-semibold text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-400 py-4">No todos found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($todosCompleted > 0)
                <div class="mt-6">
                    <form action="{{ route('todo.deleteAllCompleted') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-primary-button class="bg-red-600 hover:bg-red-700">
                            Delete All Completed Task
                        </x-primary-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
