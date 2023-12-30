<x-app-layout>
    <x-search />
    <div class="py-12" id="container">
        @if ($trendingPost)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="pb-1 text-xl max-sm:    px-2">
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
                    {{ __('Create new article') }}
                </a>
            </div>
        @endif
        @if (count($posts))
            <div class="pb-1 text-lg mt-8 max-lg:px-6 max-lg:max-w-3xl mx-auto">
                {{ __('Latest articles') }}
            </div>
            <div class="lg:grid lg:grid-cols-3 lg:gap-4 lg:h-[16rem] max-lg:max-w-3xl max-lg:px-6 mx-auto">
                @for ($i = 0; $i < count($posts); $i++)
                    <x-home.slider-item :post="$posts[$i]" />
                @endfor
            </div>
            <div class="text-right max-lg:px-6 mt-4 text-xl hover:text-sky-500 text-sky-400">
                <a href="{{route('post.articles')}}">
                    {{ __('See all articles') }}
                </a>
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
