<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12" id="container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="pb-1 text-lg">
                Trending article
            </div>
            <div>
                @if ($trendingPost->image)
                    <div class="relative h-[30rem] bg-black/20 rounded-[1rem] bg-cover bg-center group"
                        style="background-image: url({{ asset($trendingPost->image) }})">
                    @else
                        <div class="relative h-[30rem] bg-black/20 rounded-[1rem]">
                @endif
                <div
                    class="post absolute bottom-0 pb-5 pt-4 bg-black/70 text-white w-full px-4 min-h-[8rem] grid rounded-b-[1rem] grid-flow-row grid-rows-3 group-hover:animate-expand-to-full animate-shrink-from-full opacity-0">
                    <div class="text-3xl font-bold tracking-wider row-span-2">
                        <div>
                            {{ $trendingPost->title }}
                        </div>
                        <div
                            class="w-[60%] text-lg font-normal opacity-0 group-hover:animate-opacity-to-100 mt-2 animate-opacity-to-0 pointer-events-none">
                            {{ $trendingPost->snippet }}
                        </div>
                    </div>

                    <div class="flex text-xl gap-3 w-full justify-end items-end">
                        <div>
                            {{ $trendingPost->author }}
                        </div>
                        <div>
                            {{ date('m-d-Y', strtotime($trendingPost->created_at)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-1 text-lg mt-8">
            Latest articles
        </div>
        <div class="grid grid-cols-3 gap-4 h-[14rem]">
            @for ($i = 0; $i < 3; $i++)
                <x-home.slider-item :post="$posts[$i]" :id="$i" />
            @endfor
        </div>
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

    document.onload(setTimeout(showElements, 500))
</script>
