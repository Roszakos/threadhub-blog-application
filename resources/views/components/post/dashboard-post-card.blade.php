@props(['post'])

<div x-data="postCard" @@click="redirect('{{ route('post.view', $post->slug) }}')"
    class="border-2 relative bg-slate-200 col-span-1 h-[10rem] cursor-pointer hover:bg-slate-300 bg-center bg-cover"
    style="background-image: url({{ asset($post->image) }})">
    <div class="absolute top-0 w-full h-full bg-white/[0.85]">
        <div class="flex justify-between w-[94%] mt-3 m-auto">
            <div class="font-semibold text-xl">
                {{ $post->title }}
            </div>
        </div>
        <div class="absolute bottom-3 left-[3%] text-sm flex justify-between w-[94%]">
            <div class="flex flex-col w-[35%]">
                <div class="flex w-full justify-between">
                    <div>
                        {{ __('Created: ') }}
                    </div>
                    <div>
                        {{ date('m-d-Y H:i', strtotime($post->created_at)) }}
                    </div>
                </div>
                <div class="flex w-full justify-between">
                    <div>
                        {{ __('Last Edit: ') }}
                    </div>
                    <div>
                        {{ date('m-d-Y H:i', strtotime($post->updated_at)) }}
                    </div>
                </div>
            </div>
            <div class="flex gap-2 h-9">
                <div class="!bg-gray-400 hover:!bg-gray-500 text-black inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs  uppercase tracking-widest shadow-sm  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @@click.stop.prevent="redirect('{{ route('post.edit', $post->slug) }}')">
                    {{ __('Edit') }}
                </div>
                <x-post.delete-post-form :slug="$post->slug" :name="__('delete-') . $post->slug" @@click.stop />
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('postCard', () => ({
            redirect(URL) {
                window.location.href = URL
            }
        }))
    })
</script>
