@props(['title', 'created', 'updated', 'slug'])

<a href="{{ route('post.view', $slug) }}" class="relative bg-slate-200 col-span-1 h-[10rem]">
    <div class="font-semibold text-xl mt-3 ml-5">
        {{ $title }}
    </div>
    <div class="absolute bottom-3 left-[1.25rem] text-sm flex justify-between w-[30%] flex-col">
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
                {{ __('Last update: ') }}
            </div>
            <div>
                {{ date('m-d H:i', strtotime($updated)) }}
            </div>
        </div>
    </div>
</a>
