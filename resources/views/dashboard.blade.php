<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        <div class="text-lg">
                            {{ __('Your posts') }}
                        </div>
                        <a href="{{ route('post.create') }}"
                            class="bg-sky-400 hover:bg-sky-500 text-black inline-flex items-center px-4 py-3 border border-gray-300 rounded-md font-semibold text-xs  uppercase tracking-widest shadow-sm  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('+ Add new post') }}
                        </a>
                    </div>

                    <div class="grid gap-4 grid-cols-2 mt-5">
                        @foreach ($posts as $post)
                            <x-post.dashboard-post-card :post="$post" />
                        @endforeach
                    </div>
                    <div class="mt-5">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
