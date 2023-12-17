@props(['comment', 'accountOwner' => false])
<div class="py-2 group">
    @if ($accountOwner)
        <div class="w-[95%] text-right mx-auto">
            <x-danger-button class="!py-1" x-on:click="$dispatch('open-modal', 'delete-comment-{{ $comment->id }}')">
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    @endif

    <a href="{{ route('post.view', $comment->postSlug) }}#comment-{{ $comment->id }}"
        class="relative block  w-[95%] px-5 py-2 min-h-[8rem] first-line: m-auto bg-gray-200/50 mt-2 transition 
        group-hover:shadow-[0_0_10px_2px_rgba(0,0,0,0.2)] hover:!shadow-[0_0_15px_6px_rgba(0,0,0,0.3)]">
        <div class="flex justify-between h-full pb-2">
            <div class="font-semibold text-lg">
                {{ $comment->author }}
            </div>
            <div class="shrink-0">
                {{ $comment->posted }}
            </div>
        </div>
        <div>
            {{ $comment->content }}
        </div>
    </a>
    <x-modal name="delete-comment-{{ $comment->id }}">
        <form method="post" action="{{ route('comment.destroy', $comment->id) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your comment?') }}
            </h2>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete comment') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
