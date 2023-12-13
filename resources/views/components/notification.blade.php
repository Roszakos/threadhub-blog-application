@props(['status', 'message'])
<div x-data="{
    show: true,
    init() {
        setTimeout(() => {
            this.show = false
        }, 2500)
    }
}" x-show="show" x-transition.duration.400ms
    class="fixed bottom-4 py-4 px-3 left-4 w-[20rem] {{$status === 'error' ? 'bg-red-500' : 'bg-green-500'}}">
    {{ $message }}
</div>
