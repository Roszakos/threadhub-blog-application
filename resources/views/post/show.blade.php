<x-app-layout>
    <div class="w-full">
        <div class="text-3xl font-bold text-center pt-3 pb-12 leading-7 tracking-wider h-[20rem] bg-gray-200 bg-cover bg-center relative"
            style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url({{ asset($post->image) }})">
            <div class="w-full bg-black/60 py-9 absolute bottom-0 px-4">
                <div class="max-w-6xl mx-auto text-left text-white">
                    {{ $post->title }}
                </div>

                <div class="flex items-end justify-between max-w-6xl mx-auto pt-3">
                    <x-post.votes :upvotes="$upvotes" :downvotes="$downvotes" :vote="$vote" :postId="$post->id" />

                    <div class="text-left text-white items-end mt-2 flex gap-3 justify-end font-normal">
                        <div class="text-xl">{{ $post->author }}</div>
                        <div class="text-xl">{{ date('Y-m-d H:i', strtotime($post->created_at)) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-100 overflow-hidden shadow-sm min-h-[100vh] pb-10 max-w-3xl mx-auto">
            <div>
                @foreach ($post->sections as $section)
                    <div class="text-2xl font-semibold pt-4 px-10">
                        {{ $section['title'] }}
                    </div>
                    <p class="text-md font-normal px-14 whitespace-pre-line">
                        {{ $section['content'] }}
                    </p>
                @endforeach
                <div class="mt-3 w-full text-right px-3">
                    <span class="italic font-semibold">{{$post->author}}</span>
                    <span>, {{ date('Y-m-d H:i', strtotime($post->created_at)) }}</span>
                </div>
            </div>
            <div class="mt-10">
                <x-post.comment-section :comments="$comments" :postId="$post->id"/>
            </div>
        </div>
    </div>
</x-app-layout>
