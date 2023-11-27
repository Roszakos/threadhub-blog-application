@props(['post', 'id'])

{{-- @if ($post->image)
<div class="relative h-[14rem] bg-black/20 rounded-[1rem] bg-cover bg-center"
    style="background-image: url({{ asset($post->image) }})">
@else 
<div class="relative h-[14rem] bg-black/20 rounded-[1rem]">
@endif
    <div class="absolute bottom-0 pb-2 pt-2 bg-black/70 text-white w-full px-4 min-h-[100px] rounded-b-[1rem] grid grid-flow-row grid-rows-3">
        <div class="text-xl font-bold tracking-wider row-span-2">
            {{ $post->title }}
        </div>

        <div class="flex text-lg gap-3 w-full justify-end items-end">
            <div>
                {{ $post->author }}
            </div>
            <div>
                {{ date('m-d-Y', strtotime($post->created_at)) }}
            </div>
        </div>
    </div>
</div> --}}

@if ($post->image)
    <div class="relative bg-black/20 rounded-[1rem] bg-cover bg-center group"
        style="background-image: url({{ asset($post->image) }})">
    @else
        <div class="relative bg-black/20 rounded-[1rem]">
@endif
<div
    class="post absolute bottom-0 pb-2 pt-2 bg-black/70 text-white w-full px-4 min-h-[100px] rounded-b-[1rem] grid grid-flow-row grid-rows-3
         group-hover:animate-expand-to-full animate-shrink-from-full opacity-0">
    <div class="text-xl font-bold tracking-wider row-span-2">
        <div>
            {{ $post->title }}
        </div>
        <div class="text-lg font-normal opacity-0 group-hover:animate-opacity-to-100 mt-2 animate-opacity-to-0 pointer-events-none">
            {{$post->snippet}}
        </div>
    </div>

    <div class="flex text-lg gap-3 w-full justify-end items-end row-start-3">
        <div>
            {{ $post->author }}
        </div>
        <div>
            {{ date('m-d-Y', strtotime($post->created_at)) }}
        </div>
    </div>
</div>
</div>


