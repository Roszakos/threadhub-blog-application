<x-app-layout>
    @if (session('error'))
            <x-notification status="error" :message="session('error')"/>
        @endif
        @if (session('success'))
            <x-notification status="success" :message="session('success')"/>
        @endif
    <div class="w-full">
        @if ($post->image)
        <div class="text-3xl font-bold text-center pt-3 pb-12 leading-7 tracking-wider h-[20rem] bg-gray-200 bg-cover bg-center relative"
            style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url({{ asset($post->image) }})">
        @else 
        <div class="text-3xl font-bold text-center pt-3 pb-12 leading-7 tracking-wider h-[20rem] bg-sky-300  relative">
        @endif
            <div class="w-full bg-black/60 py-9 absolute bottom-0 px-4">

                <div class="max-w-6xl mx-auto flex justify-between">
                    <div class="  text-white">
                        {{ $post->title }}
                    </div>
                    @if ($isOwner)
                        <div class="flex gap-2">
                            <a href="{{ route('post.edit', $post->slug) }}">
                                <x-secondary-button>
                                    {{ __('Edit post') }}
                                </x-secondary-button>
                            </a>
                            <x-danger-button x-data=""
                                x-on:click.stop="$dispatch('open-modal', 'delete-{{ $post->slug }}')">
                                {{ __('Delete post') }}
                            </x-danger-button>
                            <x-modal :name="__('delete-') . $post->slug" focusable>
                                <form method="post" action="{{ route('post.destroy', $post->slug) }}"
                                    class="p-6 text-left">
                                    @csrf
                                    @method('delete')

                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ __('Are you sure you want to delete your post?') }}
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ __('Once your post is deleted, you will not be able to retrieve it.') }}
                                    </p>

                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>

                                        <x-danger-button class="ms-3">
                                            {{ __('Delete post') }}
                                        </x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                        </div>
                    @endif
                </div>
                <div class="flex items-end justify-between max-w-6xl mx-auto pt-3">
                    <x-post.votes :upvotes="$upvotes" :downvotes="$downvotes" :vote="$vote" :postId="$post->id" />

                    <div class="text-left text-white items-end mt-2 flex gap-3 justify-end font-normal">
                        <a href="{{ route('user.show', $post->user_id) }}" class="text-xl hover:underline">
                            {{ $post->author }}
                        </a>
                        <div class="text-xl">
                            {{ date('Y-m-d H:i', strtotime($post->created_at)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-100 overflow-hidden shadow-sm min-h-[90vh] pb-10 max-w-3xl mx-auto">
            <div>
                <div class="trix-content max-w-xl py-4 break-words m-auto">
                    {!! $post->body !!}
                </div>
                <div class="mt-3 w-full text-right px-3">
                    <a href="{{ route('user.show', $post->user_id) }}" class="italic font-semibold hover:underline">
                        {{ $post->author }}
                    </a>
                    <span>
                        {{ __(', ') . date('Y-m-d H:i', strtotime($post->created_at)) }}
                    </span>
                </div>

            </div>
            <div class="mt-10 px-3">
                <div class=" font-semibold text-lg">
                    {{ $commentsAmount . __(' Comments') }}
                </div>
                <x-comment.add-comment-form :post-id="$post->id" />
                @if ($commentsAmount > 0)
                    <x-comment.comment-section :comments="$comments" :postId="$post->id" :depth="0" />
                @else
                    <div class="text-center text-xs text-gray-600">
                        {{ __('This thread has no comments. Feel free to start a discussion.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const fragment = window.location.hash;

        if (fragment) {
            const element = document.querySelector(fragment);

            if (element) {
                element.firstElementChild.classList.remove('bg-slate-300')
                element.firstElementChild.classList.remove('border-slate-500')
                element.firstElementChild.classList.add('bg-slate-400/80')
                element.firstElementChild.classList.add('border-sky-500')
                const scroll = element.offsetTop;
                window.scrollTo({
                    top: scroll,
                    behavior: 'smooth'
                });
            }
        }
    });
</script>
