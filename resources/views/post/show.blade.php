<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white overflow-hidden shadow-sm min-h-[100vh] pb-10">
                <div class="text-3xl px-2 font-bold text-center w-full pt-3 pb-12 leading-7 tracking-wider">
                    {{ $post->title }}
                </div>
                <div>
                    @foreach ($postSections as $section)
                        <div class="text-2xl font-semibold pt-4 px-10">
                            {{ $section['title'] }}
                        </div>
                        <p class="text-md font-normal px-14 whitespace-pre-line">
                            {{ $section['content'] }}
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
