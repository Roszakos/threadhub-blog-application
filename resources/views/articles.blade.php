<x-app-layout>
    <x-search :searchValue="$search"/>
    <div class="min-h-screen bg-gray-50">
        <div id="container" class="max-w-3xl m-auto">
        <div class="min-h-[84vh] bg-gray-50 m-auto pt-3">
            @if ($search)
                <div class="font-semibold py-3 px-4 text-lg">
                    @if (count($posts) == 1)
                        {{ count($posts) . __(' article match your search.') }}
                    @else
                        {{ count($posts) . __(' articles match your search.') }}
                    @endif
                </div>
            @endif
            @if (count($posts))
                @foreach ($posts as $post)
                    <x-articles.post-card :post="$post" />
                @endforeach
                <div class="px-4 py-2">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-6">
                    {{ __('No articles available') }}
                </div>
            @endif

        </div>
    </div>
    </div>
</x-app-layout>
