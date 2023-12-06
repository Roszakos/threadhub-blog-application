<x-app-layout>
    <div id="container" class="max-w-2xl m-auto">
        <div class="min-h-[10rem] bg-white m-auto mt-4">
            <div class="p-4">
                <div class="flex justify-between">
                    <div class="flex gap-2">
                        <div>
                            <x-user-profile.user-default-icon />
                        </div>
                        <div class="flex flex-col">
                            <div class="font-semibold text-2xl tracking-wide">
                                {{ $user->nickname }}
                            </div>
                            <div class="flex gap-1">
                                <div>
                                    {{ $user->first_name }}
                                </div>
                                <div class="font-medium">
                                    {{ $user->last_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        {{ __('Joined us ') . date('m-d-Y', strtotime($user->created_at)) }}
                    </div>
                </div>
                @if ($user->description)
                <div class="bg-gray-100 italic h-[4rem] px-4 py-2 mt-2">
                    {{ __('Description (to do)') }}
                </div>
                @endif
            </div>
            <div x-data="{
                selected: 'posts',
                isSelected(navPart) {
                    if (this.selected == navPart) {
                        return true
                    } else {
                        return false
                    }
                }
            }" class="mt-4">
                <div class="flex gap-4 tracking-wider px-4">
                    <div class="cursor-pointer pb-2 border-blue-300 hover:border-b-2 hover:border-gray-300"
                        x-bind:class="{ 'border-b-2 hover:border-blue-300': isSelected('posts') }"
                        x-on:click="selected = 'posts'">
                        {{ __('Posts') }}
                    </div>
                    <div class="cursor-pointer border-blue-300 pb-2 hover:border-b-2 hover:border-gray-300"
                        x-bind:class="{ 'border-b-2 hover:border-blue-300': isSelected('comments') }"
                        x-on:click="selected = 'comments'">
                        {{ __('Comments') }}
                    </div>
                </div>
                <div class="mt-4">
                    <template x-if="isSelected('posts')">
                        <div>
                            @foreach ($posts as $post)
                                <x-user-profile.post-card :post="$post" />
                            @endforeach
                        </div>
                    </template>
                    <template x-if="isSelected('comments')">
                        <div>
                            @foreach ($comments as $comment)
                                <x-user-profile.comment-card :comment="$comment" />
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
