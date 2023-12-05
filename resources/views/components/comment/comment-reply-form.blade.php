@props(['commentId'])
<div x-data="{
    replyAuthor: null,
    replyContent: null
}" class="flex w-[98%] ml-auto mt-2 gap-2">
    <div>
        <svg class="w-6 h-6" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
            <rect fill="none" />
            <polyline fill="none" points="176 104 224 152 176 200" stroke="#000" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="16" />
            <path d="M32,56a96,96,0,0,0,96,96h96" fill="none" stroke="#000" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="16" />
        </svg>
    </div>
    <div class="w-full">
        <form x-on:submit.prevent="addComment(replyAuthor, replyContent, {{ $commentId }})">
            <div class="flex w-full justify-between items-end">
                @guest
                    <x-text-input x-model="replyAuthor" required placeholder="Your nickname..." class="block w-1/2" />
                    <x-primary-button class="py-3">
                        {{ __('Reply') }}
                    </x-primary-button>
                @else
                    <div class="text-xl font-semibold">
                        {{ Auth::user()->nickname }}
                    </div>
                    <x-primary-button class="!py-1">
                        {{ __('Reply') }}
                    </x-primary-button>
                @endguest

            </div>
            <x-textarea-input x-model="replyContent" required placeholder="Write your thoughts..." rows="4"
                class="mt-2 w-full" />
        </form>
        <template x-if="replyError">
            <div class="px-2 text-red-500 font-semibold italic">
                Comment could not be added. Try again later.
            </div>
        </template>
    </div>
</div>
