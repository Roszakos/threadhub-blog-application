@props([
    'comments' => [],
    'postId' => null,
    'parent' => null,
    'depth',
])
@php
    $nickname = Auth::user() ? Auth::user()->nickname : '';
    if ($depth > 3 || $depth === 0) {
        $containerWidth = 'w-full';
    } else {
        $containerWidth = 'w-[95%]';
    }
@endphp



<div class="{{ $containerWidth }} ml-auto">
    @foreach ($comments as $comment)
        <div id="comment{{ $comment->id }}">
            <x-comment.comment-card :comment="$comment">
                @if ($depth > 3)
                    <div
                        class="w-[95%] ml-auto bg-yellow-200 rounded-md border border-yellow-500 font-semibold my-1 px-2 py-1">
                        @if (strlen($parent->content) > 50)
                            {{ $parent->author . __(': ') }}
                            <span class="italic font-normal">
                                {{ trim(substr($parent->content, 0, 50)) . __('...') }}
                            </span>
                        @else
                            {{ $parent->author . __(': ') }}
                            <span class="italic font-normal">
                                {{ $parent->content }}
                            </span>
                        @endif
                    </div>
                @endif
            </x-comment.comment-card>
            @if (count($comment->replies) > 0)
                @include('components.comment.comment-section', [
                    'comments' => $comment->replies,
                    'depth' => $depth + 1,
                    'parent' => $comment,
                ])
            @endif
        </div>
    @endforeach
</div>
