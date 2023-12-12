<x-app-layout>
    <x-search />
    <div id="container" class="max-w-3xl m-auto">
        <div class="min-h-screen bg-white m-auto pt-3">
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
</x-app-layout>
