@props(['slug', 'name'])
<section class="space-y-6 h-full">
    <x-danger-button x-data="" class="h-full"
        x-on:click.stop="$dispatch('open-modal', '{{ $name }}')">{{ __('Delete') }}</x-danger-button>
    <x-modal :name="$name" focusable>
        <form method="post" action="{{ route('post.destroy', $slug) }}" class="p-6">
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
</section>
