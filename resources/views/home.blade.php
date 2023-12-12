<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12" id="container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="pb-1 text-lg">
                {{__('Trending article')}}
            </div>
            <x-home.trending-post :trendingPost="$trendingPost" />
        </div>
        <div class="pb-1 text-lg mt-8">
            {{__('Latest articles')}}
        </div>
        <div class="grid grid-cols-3 gap-4 h-[16rem]">
            @for ($i = 0; $i < 3; $i++)
                <x-home.slider-item :post="$posts[$i]" />
            @endfor
        </div>
    </div>
</x-app-layout>

<script>
    function showElements() {
        let posts = document.getElementsByClassName('post');
        for (let i = 0; i < 4; i++) {
            posts[i].classList.remove('opacity-0')
            posts[i].classList.add('opacity-100')
        }
    };

    document.onload = setTimeout(showElements, 500)
</script>
