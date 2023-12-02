@props([
    'comments' => [],
    'postId',
    'userNickname',
])
@php
    $nickname = Auth::user() ? Auth::user()->nickname : '';
@endphp

<div x-data="{
    author: null,
    content: null,
    comments: {{ Js::from($comments) }},
    addComment(author, content) {
        console.log(this.comments)
        let data = {
            author: author,
            content: content,
            post_id: {{ $postId }}
        }
        axios.post('{{ route('comment.store') }}', data)
            .then((response) => {
                if (response.status == 201) {
                    response.data.author = author ?? '{{ $nickname }}'
                    response.data.owner = '{{ $nickname }}' ? true : false
                    response.data.showFull = true
                    response.data.posted = '1 second ago'
                    this.comments.unshift(response.data)
                }
            })
    },
    deleteComment(comment) {
        axios.delete('/comment/' + comment.id)
            .then((response) => {
                if (response.status == 200) {
                    this.comments.splice(this.comments.indexOf(comment), 1)
                }
            })
    },
    editComment(comment) {
        let data = {
            content: comment.editModel
        }
        axios.put('/comment/' + comment.id, data)
            .then((response) => {
                if(response.status == 200) {
                    comment.content = comment.editModel
                    comment.showEditForm = false
                }
            })
    }
}">
    <div class="w-full border-b py-3 font-semibold text-lg border-gray-600 px-3">
        {{ count($comments) . __(' Comments') }} 
    </div>
    <div class="mt-4 px-3">
        <form x-on:submit.prevent="addComment(author, content)">
            <div class="flex w-full justify-between items-end">
                @guest
                    <x-text-input name="author" x-model="author" required placeholder="Your nickname..." class="block w-1/2" />
                @else
                    <div class="text-lg font-semibold">
                        {{ Auth::user()->nickname }}
                    </div>
                @endguest
                <x-primary-button>
                    {{__('Comment')}}
                </x-primary-button>
            </div>
            <x-textarea-input name="content" x-model="content" required placeholder="Write your thoughts..."
                rows="4" class="mt-2 w-full" />
        </form>

        <div>
            <template x-for="comment in comments">
                <x-post.comment />
            </template>
        </div>
    </div>
</div>

<script></script>
