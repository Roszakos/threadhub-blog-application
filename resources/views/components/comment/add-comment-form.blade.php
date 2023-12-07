@props(['postId'])
<div x-data="{
    author: null,
    content: null,
    commentError: false,
    addComment(author, content) {
        this.commentError = false
        let data = {
            author: author,
            content: content,
            post_id: {{$postId}}
        }
        axios.post('{{route('comment.store')}}', data)
            .then((response) => {
                if (response.status == 201) {
                    location.reload()
                } else {
                    this.commentError = true
                }
            })
            .catch((err) => {
                this.commentError = true
            })
    },
}" class=" mt-2 gap-2">
    <div class="w-full">
        <form x-on:submit.prevent="addComment(author, content)">
            <div class="flex w-full justify-between items-end">
                @guest
                    <x-text-input x-model="author" required placeholder="Your nickname..." class="block w-1/2" />
                    <x-primary-button class="py-3">
                        {{ __('Comment') }}
                    </x-primary-button>
                @else
                    <div class="text-xl font-semibold">
                        {{ Auth::user()->nickname }}
                    </div>
                    <x-primary-button class="!py-1">
                        {{ __('Comment') }}
                    </x-primary-button>
                @endguest

            </div>
            <x-textarea-input x-model="content" maxlength="1000" required placeholder="Write your thoughts..." rows="4"
                class="mt-2 w-full" />
        </form>
        <template x-if="commentError">
            <div class="px-2 text-red-500 font-semibold italic">
                Comment could not be added. Try again later.
            </div>
        </template>
    </div>
</div>
