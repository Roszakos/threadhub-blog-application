<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Edit profile information -->
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nickname" :value="__('Nickname')" />
            <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full" :value="old('nickname', $user->nickname)"
                required autofocus autocomplete="nickname" />
            <x-input-error class="mt-2" :messages="$errors->get('nickname')" />
        </div>

        <div>
            <x-input-label value="Profile picture" class="py-2 font-semibold" />
            @if ($user->image)
                <div class="bg-cover bg-center w-48 h-48 group rounded-full" id="chosenImage"
                    style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url({{ asset($user->image) }})">
                    <div class="w-full h-full hidden bg-white/70 group-hover:flex items-center justify-center">
                        <x-secondary-button x-data="" class="hover:bg-gray-200" x-on:click="removeImage">
                            {{__('Remove Image')}}
                        </x-secondary-button>
                    </div>
                </div> 
            @else
                <div class="bg-cover bg-center w-48 h-48 rounded-full hidden group" id="chosenImage">
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
            
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-textarea-input id="description" name="description" type="text" rows="4" class="mt-1 block w-full" :value="old('description', $user->description)" autofocus autocomplete="description">
                {{old('description', $user->description)}}
            </x-textarea-input>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div>
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)"
                autofocus autocomplete="first_name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)"
                autofocus autocomplete="last_name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>


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