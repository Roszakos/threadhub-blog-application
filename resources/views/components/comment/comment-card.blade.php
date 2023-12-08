@props(['comment'])
@aware(['postId'])

<div x-data="{
    showFull: true,
    showEditForm: false,
    editError: false,
    showReplyForm: false,
    replyError: false,
    init() {
        if ({{ strlen($comment->content) }} > 260) {
            this.showFull = false
        }
    },
    addComment(author, content) {
        let data = {
            author: author,
            content: content,
            post_id: {{ $postId }},
            parent_id: {{ $comment->id }}
        }
        axios.post('{{ route('comment.store') }}', data)
            .then((response) => {
                if (response.status == 201) {
                    window.location.hash = 'comment-' + response.data.id
                    location.reload()
                } else {
                    this.replyError = true
                }
            })
            .catch((err) => {
                this.replyError = true
            })
    },
    deleteComment() {
        axios.delete('{{ route('comment.destroy', $comment->id) }}')
            .then((response) => {
                if (response.status == 200) {
                    document.getElementById('comment{{ $comment->id }}').remove()
                }
            })
    },
    editComment() {
        let content = document.getElementById('edit-content-{{ $comment->id }}').value
        let data = {
            content: content
        }
        axios.put('{{ route('comment.update', $comment->id) }}', data)
            .then((response) => {
                if (response.status == 200) {
                    window.location.hash = 'comment-{{$comment->id}}'
                    location.reload()
                } else {
                    this.editError = true
                }
            })
            .catch((err) => {
                this.editError = true
            })

    }
}" class="mt-4" id="comment-{{ $comment->id }}">
    {{ $slot }}
    <div class="flex py-2 bg-slate-300 border border-slate-500 rounded-md  min-h-[6rem]">

        <div class="w-1/5 border-r border-black px-3 flex flex-col justify-between">
            @if ($comment->user_id)
            <a href="{{route('user.show', $comment->user_id)}}" class="font-semibold text-xl hover:text-sky-700 transition">
                {{ $comment->author }}
            </a>
            @else
            <div class="font-semibold text-xl">
                {{ $comment->author }}
            </div>
            @endif
            <div>
                {{ $comment->posted }}
            </div>
        </div>

        <div class="px-1 w-4/5 flex flex-col justify-between pl-3">
            <div class="flex justify-between gap-2 ">
                <template x-if="!showFull">
                    <div class="first-line:leading-[0rem]">
                        <span id="comment-content-{{ $comment->id }}" class="whitespace-pre-line ">
                            {{ trim(substr($comment->content, 0, 250)) . __('...') }}
                        </span>
                        <span class="italic text-blue-700 cursor-pointer" x-on:click="showFull = true">
                            {{ __('[show more]') }}
                        </span>
                    </div>
                </template>
                <template x-if="showFull">
                    <div class="first-line:leading-[0rem]">
                        <span id="comment-content-{{ $comment->id }}" class="whitespace-pre-line ">
                            {{ $comment->content }}
                        </span>
                        @if (strlen($comment->content) > 260)
                            <span class="italic text-blue-700 cursor-pointer" x-on:click="showFull = false">
                                {{ __('[hide]') }}
                            </span>
                        @endif
                    </div>
                </template>
                <div class="w-4 mr-2 flex-shrink-0">
                    <svg class="w-4 h-4 cursor-pointer" id="Layer_1" style="enable-background:new 0 0 16 16;"
                        version="1.1" viewBox="0 0 16 16" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x-on:click="showReplyForm = !showReplyForm">
                        <path
                            d="M7,5V3c0-0.515-0.435-1-1-1C5.484,2,5.258,2.344,5,2.586L0.578,7C0.227,7.359,0,7.547,0,8s0.227,0.641,0.578,1L5,13.414  C5.258,13.656,5.484,14,6,14c0.565,0,1-0.485,1-1v-2h2c1.9,0.075,4.368,0.524,5,2.227C14.203,13.773,14.625,14,15,14  c0.563,0,1-0.438,1-1C16,7.083,12.084,5,7,5z" />
                    </svg>
                </div>
            </div>
            @if ($comment->owner)
                <div class="w-full px-2 pt-2 flex gap-2 justify-end">
                    <x-secondary-button class="!py-1" x-on:click="showEditForm = true">
                        {{ __('Edit') }}
                    </x-secondary-button>
                    <x-danger-button class="!py-1" x-on:click="deleteComment">
                        {{ __('Delete') }}
                    </x-danger-button>
                </div>
            @endif
        </div>
    </div>
    <template x-if="showEditForm">
        <div class="w-full px-2 bg-slate-300 py-2 min-h-[6rem] border border-slate-500 rounded-md mt-4">
            <form x-on:submit.prevent="editComment">
                <div class="flex w-full justify-between items-end">
                    @auth
                        <div class="text-lg font-semibold">
                            {{ Auth::user()->nickname }}
                        </div>
                    @endauth
                </div>
                <x-textarea-input id="edit-content-{{ $comment->id }}" rows="4" class="mt-2 w-full">
                    {{ $comment->content }}
                </x-textarea-input>
                <div class="mt-2 flex gap-2 justify-end">
                    <x-secondary-button x-data="{
                        hideEditForm() {
                            this.showEditForm = false
                            this.editError = false
                        }
                    }" x-on:click="hideEditForm" class="!py-1">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button class="!py-1">
                        {{ __('Edit') }}
                    </x-primary-button>
                </div>
            </form>
            <template x-if="editError">
                <div class="px-2 text-red-500 font-semibold italic">
                    Comment couldn't be updated. Try again later.
                </div>
            </template>
        </div>
    </template>
    <template x-if="showReplyForm">
        <x-comment.comment-reply-form :commentId="$comment->id" />
    </template>
</div>
