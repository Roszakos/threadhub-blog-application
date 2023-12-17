@props(['searchValue' => ''])
<div class="px-6 py-3 bg-white text-center">
    <form action="{{ route('post.articles') }}" method="GET" autocomplete="off">
        <x-text-input id="search-bar" class="md:w-[70%] w-full bg-gray-100" name="search"
            placeholder="Search..." :value="$searchValue" readonly="true"/>
    </form>
</div>

<script>
    function removeReadonly(ev) {
        ev.target.removeAttribute('readonly');
        ev.target.removeEventListener('click', removeReadonly)
        ev.target.removeEventListener('focus', removeReadonly)
    }
    document.getElementById('search-bar').addEventListener('click', removeReadonly)
    document.getElementById('search-bar').addEventListener('focus', removeReadonly)
</script>
