@props(['title', 'created', 'updated', 'slug'])

<div x-data="postCard" @@click="redirect('{{ route('post.view', $slug) }}')"
    class="relative bg-slate-200 col-span-1 h-[10rem] cursor-pointer hover:bg-slate-300">
    <div class="flex justify-between w-[94%] mt-3 m-auto">
        <div class="font-semibold text-xl">
            {{ $title }}
        </div>
        <div @@click.stop="redirect('{{ route('post.edit', $slug) }}')"
            href="{{ route('post.edit', $slug) }}"
            class="py-1 px-5 text-md bg-slate-400 ring-1 ring-black rounded-sm cursor-pointer
        hover:bg-slate-500 hover:ring-2">
            Edit
        </div>
    </div>
    <div class="absolute bottom-3 left-[3%] text-sm flex justify-between w-[30%] flex-col">
        <div class="flex w-full justify-between">
            <div>
                {{ __('Created: ') }}
            </div>
            <div>
                {{ date('m-d H:i', strtotime($created)) }}
            </div>
        </div>
        <div class="flex w-full justify-between">
            <div>
                {{ __('Last Edit: ') }}
            </div>
            <div>
                {{ date('m-d H:i', strtotime($updated)) }}
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
