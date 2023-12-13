<x-app-layout>

    <x-search />


    <div class="py-12" id="container">
        @if ($trendingPost)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="pb-1 text-lg">
                    {{ __('Trending article') }}
                </div>
                <x-home.trending-post :trendingPost="$trendingPost" />
            </div>
        @else
            <div class="text-center font-bold text-lg">
                <span>
                    {{ __('No articles have been published yet. ') }}
                </span>
                <a href="{{ route('post.create') }}" class="text-sky-300 hover:text-sky-400 hover:underline">
                    {{ __('Create new post') }}
                </a>
            </div>
        @endif
        @if (count($posts))
            <div class="pb-1 text-lg mt-8">
                {{ __('Latest articles') }}
            </div>
            <div class="grid grid-cols-3 gap-4 h-[16rem]">
                @for ($i = 0; $i < 3; $i++)
                    <x-home.slider-item :post="$posts[$i]" />
                @endfor
            </div>
        @endif

    </div>
</x-app-layout>

<script>
    function showElements() {
        let posts = document.getElementsByClassName('post');
        for (let i = 0; i < posts.length; i++) {
            posts[i].classList.remove('opacity-0')
            posts[i].classList.add('opacity-100')
        }
    };

    document.onload = setTimeout(showElements, 500)
</script>
