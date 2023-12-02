<div x-data="{
    init() {
        comment.content.length > 260 ? comment.showFull = false : comment.showFull = true
        comment.showEditForm = false
    }
}">
    <template x-if="!comment.showEditForm">
        <div class="flex py-2 min-h-[6rem] bg-slate-300 border border-slate-500 rounded-md mt-4">
            <div class="w-1/5 border-r border-black px-3 flex flex-col justify-between">
                <div x-text="comment.author" class="font-semibold text-xl">
                </div>
                <div x-text="comment.posted">
                </div>
            </div>

            <div class="px-3 w-4/5 flex flex-col justify-between">
                <div>
                    <template x-if="comment.content.length > 260 && !comment.showFull">
                        <div>
                            <span x-text="comment.content.substring(0,  250).trim() + '...'">
                            </span>
                            <span class="italic text-blue-700 cursor-pointer" x-on:click="comment.showFull = true">
                                {{ __('[show more]') }}
                            </span>
                        </div>
                    </template>
                    <template x-if="comment.showFull">
                        <div>
                            <span x-text="comment.content"></span>
                            <template x-if="comment.content.length > 260">
                                <span class="italic text-blue-700 cursor-pointer" x-on:click="comment.showFull = false">
                                    {{ __('[hide]') }}
                                </span>
                            </template>
                        </div>
                    </template>
                </div>
                <template x-if="comment.owner">
                    <div class="w-full px-2 pt-2 flex gap-2 justify-end">
                        <x-secondary-button class="!py-1" x-on:click="comment.showEditForm = true">
                            {{ __('Edit') }}
                        </x-secondary-button>
                        <x-danger-button x-data=""
                            x-on:click.stop="$dispatch('open-modal', 'delete-' + comment.id)" class="!py-1">
                            {{ __('Delete') }}
                        </x-danger-button>
                        <x-post.delete-comment-modal>
                            <div class="py-2 px-3">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Are you sure you want to delete your comment?') }}
                                </h2>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ms-3"
                                        x-on:click="deleteComment(comment), $dispatch('close')">
                                        {{ __('Delete comment') }}
                                    </x-danger-button>
                                </div>
                            </div>
                        </x-post.delete-comment-modal>
                    </div>
                </template>
            </div>
        </div>
    </template>
    <template x-if="comment.showEditForm">
        <div class="w-full px-2 bg-slate-300 py-2 min-h-[6rem] border border-slate-500 rounded-md mt-4">
            <form x-on:submit.prevent="editComment(comment)">
                <div class="flex w-full justify-between items-end" 
                    x-init="comment.editModel = comment.content">
                    @auth
                        <div class="text-lg font-semibold">
                            {{ Auth::user()->nickname }}
                        </div>
                    @endauth

                    
                </div>
                <x-textarea-input rows="4" class="mt-2 w-full" x-model="comment.editModel" name="content"/>
                <div class="mt-2 flex gap-2 justify-end">
                        <x-secondary-button
                            x-data="{
                                hideEditForm() {
                                    this.comment.showEditForm = false
                                    this.comment.editError = false
                                }
                            }" x-on:click="hideEditForm" class="!py-1"
                        >
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button class="!py-1">
                            {{ __('Edit') }}
                        </x-primary-button>
                    </div>
            </form>
            <template x-if="comment.editError">
                <div class="px-2 text-red-500 font-semibold italic">
                    Comment couldn't be updated. Try again later.
                </div>
            </template>
        </div>
    </template>
</div>
