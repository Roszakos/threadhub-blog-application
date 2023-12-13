@props(['searchValue' => ''])
<div class="px-6 py-3 bg-white text-center">
    <form action="{{route('post.articles')}}" method="GET">
        <x-text-input class="w-[70%] bg-gray-100" name="search" placeholder="Search..." :value="$searchValue"/>
    </form>
</div>