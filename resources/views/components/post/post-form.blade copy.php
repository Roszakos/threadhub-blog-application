@props([
    'items' => [1],
    'title' => '',
    'subtitles' => [null],
    'contents' => [null],
    'titleErrors' => [null],
    'contentErrors' => [null],
    'postAction' => route('post.store'),
    'action' => 'create',
])

@if (old('title'))
    @php
        $title = old('title');
    @endphp
@endif

<div class="p-6 text-gray-900">
    <form method="post" action="{{ $postAction }}" enctype="multipart/form-data" x-data="form" id="myForm">
        @csrf
        <div class="pb-1 w-3/4">
            {{-- Title --}}
            <x-input-label value="Title" class="py-2 font-semibold !text-lg" />
            <x-text-input class="w-full" name="title" :value="$title" autofocus required />
            <x-input-error :messages="$errors->get('title')" />

            {{-- Categories --}}

            {{-- Image --}}
            <x-input-label value="Article Image" class="py-2 font-semibold " />
            <img id="chosenImage" class="w-2/5 pb-2" />
            <label
                class="border-[1px] border-black inline-block py-2 px-3 cursor-pointer rounded-lg uppercase text-xs font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 tracking-widest">
                <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" x-on:change="showImage"
                    class="hidden" />
                Choose image
            </label>
        </div>

        @if (old('subtitle'))
            @for ($i = 0; $i < count(old('subtitle')); $i++)
                @php
                    $items[$i] = $i;
                    $subtitles[$i] = old('subtitle')[$i];
                    $contents[$i] = old('content')[$i];
                @endphp
                @if ($errors->get('subtitle.' . $i))
                    @php
                        $titleErrors[$i] = $errors->get('subtitle.' . $i)[0];
                    @endphp
                @else
                    @php
                        $titleErrors[$i] = null;
                    @endphp
                @endif
                @if ($errors->get('content.' . $i))
                    @php
                        $contentErrors[$i] = $errors->get('content.' . $i)[0];
                    @endphp
                @else
                    @php
                        $contentErrors[$i] = null;
                    @endphp
                @endif
            @endfor
        @endif
        <div>
            <div
                x-data='createModel(@json($items), @json($subtitles), @json($contents), @json($titleErrors), @json($contentErrors))'>
                <template x-for="i in items.length">
                    <div>
                        <div class="flex justify-between font-medium text-md mt-8 w-3/4">
                            <div>
                                {{ __('Section ') }}
                                <span x-text="i"></span>
                            </div>
                            <div class="flex gap-2">
                                <x-secondary-button x-show="items.length > 1" class="!bg-red-400 hover:!bg-red-500"
                                    x-on:click="removeSection(i - 1)">
                                    {{ __('Remove section') }}
                                </x-secondary-button>
                                <x-secondary-button class="!bg-gray-300 hover:!bg-gray-400" x-on:click="addSection(i)">
                                    {{ __('+ Add Section') }}
                                </x-secondary-button>
                            </div>
                        </div>
                        <template x-if="model[i - 1]">
                            <div>
                                <x-input-label value="Subtitle" class="pb-2 mt-[-0.5rem]" />
                                <x-text-input class="w-3/4" x-model="model[i - 1].subtitle" name="subtitle[]"
                                    ::value="model[i - 1].subtitle" required />
                                <div class='text-sm text-red-600 space-y-1' x-text="titleErrors[i-1]">
                                </div>


                                <x-input-label value="Text" class="py-2 mt-2" />
                                <x-textarea-input rows="7" class="w-3/4" x-model="model[i - 1].content"
                                    name="content[]" ::value="model[i - 1].content" required />
                                <div class='text-sm text-red-600 space-y-1' x-text="contentErrors[i-1]">
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <div class="text-right w-3/4 mt-3">
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
    document.addEventListener('alpine:init', () => {
        Alpine.data('form', () => ({
            addSection(index) {
                this.items.splice(index, 0, 1)
                this.model.splice(index, 0, {
                    subtitle: null,
                    content: null
                })
            },
            removeSection(index) {
                this.model.splice(index, 1)
                this.items.splice(index, 1)
            }
        }))
    })

    function createModel(items, subtitles, contents, titleErrors, contentErrors) {
        let model = []
        for (i in items) {
            model.push({
                subtitle: subtitles[i],
                content: contents[i]
            })
        }
        return {
            items: items,
            model: model,
            titleErrors: titleErrors,
            contentErrors: contentErrors
        }
    }

    function showImage(ev) {
        const file = ev.target.files[0]

        const reader = new FileReader()
        reader.onload = () => {
            document.getElementById('chosenImage').src = reader.result
        }

        reader.readAsDataURL(file)
    }
</script>
