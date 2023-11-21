<div class="p-6 text-gray-900">
    <form method="post" action="{{ route('post.store') }}" x-data="form" id="myForm">
        @csrf
        <x-input-label value="Title" class="py-2 font-semibold !text-lg" />
        <x-text-input class="w-3/4" name="title" />
        <x-input-error messages="" />

        <div>
            <div x-data="{ items: [1], model: [{ subtitle: undefined, content: undefined }] }">
                <template x-for="i in items.length">
                    <div>
                        <div class="flex justify-between font-medium text-md mt-3 w-3/4">
                            <div>
                                {{ __('Section ') }}
                                <span x-text="i"></span>
                            </div>
                        </div>
                        <x-input-label value="Subtitle" class="py-2" />
                        <x-text-input class="w-3/4" x-model="model[i-1].subtitle" name="subtitle[]" />

                        <x-input-label value="Text" class="py-2 mt-2" />
                        <x-textarea-input class="w-3/4" x-model="model[i-1].content" name="content[]" />
                    </div>
                </template>
            </div>
        </div>

        <div class="text-right w-3/4 mt-3">
            <x-primary-button>
                Add new post
            </x-primary-button>
        </div>
    </form>
</div>


