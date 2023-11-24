<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit your post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <x-post.post-form :items="array_fill(0, count($postSectionsSubtitle), 1)" :postAction="route('post.update', $post->slug)" :title="$post->title" :subtitles="$postSectionsSubtitle"
                    :contents="$postSectionsContent" action="edit" />
            </div>
        </div>
    </div>
</x-app-layout>
