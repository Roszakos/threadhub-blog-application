<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        <div class="text-lg">
                            {{ __('Your posts') }}
                        </div>
                        <a href="{{ route('post.create') }}"
                            class="py-2 px-3 bg-sky-400 ring-1 ring-black cursor-pointer rounded-sm
                            hover:bg-sky-500 hover:ring-2">
                            {{ __('+ Add new post') }}
                        </a>
                    </div>
                    <div class="grid gap-4 grid-cols-2 mt-5">
                        <div class="bg-slate-200 py-20 col-span-1">
                            {{-- {{ __('xd') }} --}}
                        </div>
                        <div class="bg-slate-200 py-20 col-span-1">
                            {{-- {{ __('xd') }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
