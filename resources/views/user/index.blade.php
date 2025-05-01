<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="px-6 pt-6 mb-5 md:w-1/2 2xl:w-1/3">
        @if (request('search'))
            <h2 class="pb-3 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Search result for : {{ request('search') }}
            </h2>
        @endif
        <form class="flex items-center gap-2">
            <div>
                <x-text-input id="search" name="search" type="text" class="w-50"
                    placeholder="Search by name or email ..." value="{{ request('search') }}" autofocus />
            </div>
            <div class="px-2">
                <x-primary-button type="submit">
                    {{ __('Search') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <div class="px-6 text-xl text-gray-900 dark:text-gray-100">
        <div class="flex items-center justify-between">
            <div></div>
            <div>
                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="pb-3 text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
                @endif
                @if (session('danger'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="pb-3 text-sm text-red-600 dark:text-red-400">{{ session('danger') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="w-full px-6">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Todo</th>
                                <th class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $data)
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td class="px-6 py-4 font-medium dark:text-white">{{ $data->id }}</td>
                                    <td class="px-6 py-4">{{ $data->name }}</td>
                                    <td class="px-6 py-4">{{ $data->email }}</td>
                                    <td class="px-6 py-4">
                                        {{ $data->todos->count() }}
                                        <span class="text-green-500">(
                                            {{ $data->todos->where('is_done', true)->count() }}
                                        </span>
                                        /
                                        <span class="text-blue-400">
                                            {{ $data->todos->where('is_done', false)->count() }}
                                        </span>)
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-4"> <!-- increased spacing -->
                                            @if ($data->is_admin)
                                                <form action="{{ route('user.removeadmin', $data) }}" method="POST" onsubmit="return confirm('Remove admin access?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-blue-500 dark:text-blue-400">Remove Admin</button>
                                                </form>
                                            @else
                                                <form action="{{ route('user.makeadmin', $data) }}" method="POST" onsubmit="return confirm('Make this user an admin?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-500 dark:text-red-400">Make Admin</button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('user.destroy', $data) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 dark:text-red-400">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-5">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
