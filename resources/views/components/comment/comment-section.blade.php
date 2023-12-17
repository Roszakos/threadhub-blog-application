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
                    <div x-data="{
                        setHighlightedComment(commentId) {
                            if ($store.highlightedComment.elementId) {
                                unhighlightComment($store.highlightedComment.elementId)
                            }
                            $store.highlightedComment.elementId = '#comment-' + commentId
                            scrollToComment(commentId)
                        }
                    }" x-on:click="setHighlightedComment({{$parent->id}})" id="reply-{{$comment->id}}"
                        class="w-[95%] ml-auto bg-yellow-200 rounded-md border border-yellow-500 font-semibold my-1 px-2 py-1 cursor-pointer">
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

<script>

    function scrollToComment(commentId) {
        const element = document.getElementById('comment-' + commentId)

        if(! element.firstElementChild.hasAttribute('id')) {
            element.firstElementChild.classList.remove('bg-slate-300')
            element.firstElementChild.classList.remove('border-slate-500')
            element.firstElementChild.classList.add('bg-slate-400/80')
            element.firstElementChild.classList.add('border-sky-500')
        } else {
            element.getElementsByTagName('div')[1].classList.remove('bg-slate-300')
            element.getElementsByTagName('div')[1].classList.remove('border-slate-500')
            element.getElementsByTagName('div')[1].classList.add('bg-slate-400/80')
            element.getElementsByTagName('div')[1].classList.add('border-sky-500')
        }

        const scroll = element.offsetTop
        window.scrollTo({
            top: scroll,
            behavior: 'smooth'
        });
    }

    function unhighlightComment(elementId) {
        const element = document.querySelector(elementId)
        if(! element.firstElementChild.hasAttribute('id')) {
            element.firstElementChild.classList.add('bg-slate-300')
            element.firstElementChild.classList.add('border-slate-500')
            element.firstElementChild.classList.remove('bg-slate-400/80')
            element.firstElementChild.classList.remove('border-sky-500')
        } else {
            element.getElementsByTagName('div')[1].classList.add('bg-slate-300')
            element.getElementsByTagName('div')[1].classList.add('border-slate-500')
            element.getElementsByTagName('div')[1].classList.remove('bg-slate-400/80')
            element.getElementsByTagName('div')[1].classList.remove('border-sky-500')
        }
    }
</script>
