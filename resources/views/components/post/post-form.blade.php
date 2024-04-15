@props([
    'title' => '',
    'image' => '',
    'body' => '',
    'postAction' => route('post.store'),
    'action' => 'create',
])

    @php
        $title = old('title') ? old('title') : $title;
        $body = old('body') ? old('body') : $body;
    @endphp

<div class="p-6 text-gray-900">
    <form method="post" action="{{ $postAction }}" enctype="multipart/form-data">
        @csrf
        <div class="pb-1 w-full md:w-3/4">
            {{-- Title --}}
            <x-input-label value="Title" class="py-2 font-semibold !text-lg" />
            <x-text-input class="w-full" name="title" :value="$title" autofocus required />
            <x-input-error :messages="$errors->get('title')" />

            {{-- Categories --}}

            {{-- Image --}}
            <x-input-label value="Article Image" class="py-2 font-semibold" />
            @if ($image)
                <div class="bg-cover bg-center h-[14rem] max-w-[30rem] group" id="chosenImage"
                    style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url({{ asset($image) }})">
                    <div class="w-full h-full hidden bg-white/70 group-hover:flex items-center justify-center">
                        <x-secondary-button x-data="" class="hover:bg-gray-200" x-on:click="removeImage">
                            {{__('Remove Image')}}
                        </x-secondary-button>
                    </div>
                </div> 
            @else
                <div class="bg-cover bg-center h-[14rem] max-w-[30rem] hidden group" id="chosenImage">
                    <div class="w-full h-full hidden bg-white/70 group-hover:flex items-center justify-center">
                        <x-secondary-button x-data="" class="hover:bg-gray-200" x-on:click="removeImage">
                            {{__('Remove Image')}}
                        </x-secondary-button>
                    </div>
                </div> 
            @endif
            <label
                class="border-[1px] border-black inline-block py-2 px-3 cursor-pointer mt-2 rounded-lg uppercase text-xs font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 tracking-widest">
                <input x-data="" type="file" id="imageInput" name="image" accept="image/png, image/jpeg, image/jpg" x-on:change="showImage"
                    class="hidden" />
                {{ __('Choose image') }}
            </label>
            <x-input-error :messages="$errors->get('image')" />
            <input type="text" id="imageAction" name="imageAction" hidden value="false"/>
        </div>

        <div class="font-medium text-md mt-8 w-full md:w-3/4">
            <input id="x" type="hidden" name="body">
            <trix-editor input="x" class="min-h-[16rem] trix-content">
                {!! $body !!}
            </trix-editor>
            <x-input-error :messages="$errors->get('body')" />
        </div>

        <div class="text-right w-full md:w-3/4 mt-3">
            <x-primary-button>
                @if ($action == 'create')
                    {{ __('Create new article') }}
                @else
                    {{ __('Edit') }}
                @endif
            </x-primary-button>
        </div>
    </form>
</div>


<script>
    function showImage(ev) {
        document.getElementById('imageAction').value = 'change';
        const file = ev.target.files[0]

        const reader = new FileReader()
        reader.onload = () => {
            document.getElementById('chosenImage').style.backgroundImage = 'linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url(' + reader.result + ')'
            document.getElementById('chosenImage').style.display = 'block'
        }

        reader.readAsDataURL(file)
    }

    function removeImage() {
        document.getElementById('imageAction').value = 'delete';
        document.getElementById('imageInput').value = '';
        document.getElementById('chosenImage').style.display = 'none'
    }
</script>
