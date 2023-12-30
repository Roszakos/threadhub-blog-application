<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard Panel') }}
        </h2>
    </x-slot>
    @if (session('error'))
        <x-notification status="error" :message="session('error')" />
    @endif
    @if (session('success'))
        <x-notification status="success" :message="session('success')" />
    @endif

    <div x-data="{
        userDeleteModal: {
            id: @js(session('userId')),
            showModal(id) {
                this.id = id
                $dispatch('open-modal', 'deleteUser')
            }
        },
        postDeleteModal: {
            slug: null,
            showModal(slug) {
                this.slug = slug
                $dispatch('open-modal', 'deletePost')
            }
        }
    }">
        <x-modal name="deleteUser" :show="$errors->userDeletion->isNotEmpty()">
            <form method="post" x-bind:action="'/user/' + userDeleteModal.id" class="p-6 text-left">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this user\'s account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('All resources linked to this account will also be deleted') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Password') }}" required />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
        <x-modal name="deletePost">
            <form method="post" x-bind:action="'/post/' + postDeleteModal.slug" class="p-6 text-left">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this article?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once the article is deleted, you won\'t be able to retrieve it') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Delete Article') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
        <div class="px-4">
            <div x-data="{
                selected: null,
                changeSelected(navItem) {
                    this.selected = navItem
                    sessionStorage.setItem('adminDashboardNavigation', navItem)
                },
                isSelected(navItem) {
                    return this.selected === navItem
                },
                init() {
                    if (sessionStorage.getItem('adminDashboardNavigation')) {
                        this.selected = sessionStorage.getItem('adminDashboardNavigation')
                    } else {
                        this.selected = 'users'
                    }
                }
            }"
                class="max-w-7xl mx-auto mt-10 py-4 bg-white rounded-md min-h-[75vh] px-4 shadow-lg">
                <div class="flex gap-4 pt-3 pl-2 text-lg font-medium tracking-wide">
                    <div x-on:click="changeSelected('users')"
                        class="cursor-pointer pb-2 border-blue-300 hover:border-b hover:border-gray-300"
                        :class="isSelected('users') ? '!border-b-2 !border-sky-300' : ''">
                        {{ __('Users') }}
                    </div>
                    <div x-on:click="changeSelected('posts')"
                        class="cursor-pointer pb-2 border-blue-300 hover:border-b hover:border-gray-300"
                        :class="isSelected('posts') ? '!border-b-2 !border-sky-300' : ''">
                        {{ __('Articles') }}
                    </div>
                </div>

                <template x-if="isSelected('users')">
                    <div>
                        <table class="w-full table-fixed px-2 mt-3">
                            <tr class="text-center">
                                <th class="w-[6%] text-right pr-4">{{ __('ID') }}</th>
                                <th class="w-[20%]">{{ __('Nickname') }}</th>
                                <th class="w-[15%] hidden md:table-cell">{{ __('First name') }}</th>
                                <th class="w-[15%] hidden md:table-cell">{{ __('Last name') }}</th>
                                <th class="w-[19%] max-[420px]:hidden">{{ __('Registered') }}</th>
                                <th class="w-[25%]">{{ __('Action') }}</th>
                            </tr>
                            @foreach ($users as $user)
                                <tr class="border-t border-gray-300 text-center">
                                    <th class="text-right pr-4">{{ $user->id }}</th>
                                    <td>{{ $user->nickname }}</td>

                                    <td class="hidden md:table-cell">
                                        @if ($user->first_name)
                                            {{ $user->first_name }}
                                        @else
                                            {{ __('-') }}
                                        @endif
                                    </td>

                                    <td class="hidden md:table-cell">
                                        @if ($user->last_name)
                                            {{ $user->last_name }}
                                        @else
                                            {{ __('-') }}
                                        @endif
                                    </td>

                                    <td class="tabular-nums max-[420px]:hidden"
                                        title="{{ date('d-m-Y H:i:s', strtotime($user->created_at)) }}">
                                        <span>
                                            {{ date('d-m-Y', strtotime($user->created_at)) }}
                                        </span>
                                        <span class="hidden lg:inline-block">
                                            {{ date('H:i', strtotime($user->created_at)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                            <a href="{{ route('user.show', $user->id) }}">
                                                <x-secondary-button class="!py-1">
                                                    {{ __('profile') }}
                                                </x-secondary-button>
                                            </a>
                                            <div>
                                                <x-danger-button class="!py-1"
                                                    x-on:click="userDeleteModal.showModal({{ $user->id }})">
                                                    {{ __('Delete') }}
                                                </x-danger-button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="px-2 mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </template>

                <template x-if="isSelected('posts')">
                    <div>
                        <table class="w-full table-fixed px-2 mt-3">
                            <tr class="text-center">
                                <th class="w-[6%] text-right pr-4">{{ __('ID') }}</th>
                                <th class="w-[26%] lg:w-[34%] text-left">{{ __('Title') }}</th>
                                <th class="w-[15%] hidden min-[420px]:table-cell">{{ __('Author') }}</th>
                                <th class="w-[8%] lg:w-[5%] hidden md:table-cell">{{ __('Views') }}</th>
                                <th class="w-[18%] lg:w-[15%] hidden sm:table-cell">{{ __('Published') }}</th>
                                <th class="w-[25%]">{{ __('Action') }}</th>
                            </tr>
                            @foreach ($posts as $post)
                                <tr class="border-t border-gray-300 text-center">
                                    <th class="text-right pr-4">{{ $post->id }}</th>
                                    <td class="truncate text-left">{{ $post->title }}</td>

                                    <td class="truncate hidden min-[420px]:table-cell">
                                        {{ $post->user->nickname }}
                                    </td>

                                    <td class="hidden md:table-cell tabular-nums">
                                        {{ $post->views }}
                                    </td>

                                    <td class="tabular-nums hidden sm:table-cell"
                                        title="{{ date('d-m-Y H:i:s', strtotime($post->created_at)) }}">
                                        {{ date('d-m-Y H:i', strtotime($post->created_at)) }}
                                    </td>
                                    <td>
                                        <div class="flex lg:flex-row flex-col gap-2 justify-center">
                                            <div class="flex flex-col min-[460px]:flex-row gap-2 justify-center">
                                                <a href="{{ route('post.view', $post->slug) }}">
                                                    <x-secondary-button class="!py-1">
                                                        {{ __('View') }}
                                                    </x-secondary-button>
                                                </a>
                                                <a href="{{ route('post.edit', $post->slug) }}">
                                                    <x-secondary-button
                                                        class="!py-1 !bg-slate-300 hover:!bg-slate-400 text-black">
                                                        {{ __('Edit') }}
                                                    </x-secondary-button>
                                                </a>
                                            </div>
                                            <div class="col-span-2">
                                                <x-danger-button class="!py-1"
                                                    x-on:click="postDeleteModal.showModal('{{ $post->slug }}')">
                                                    {{ __('Delete') }}
                                                </x-danger-button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="px-2 mt-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>
</x-app-layout>

<style>

</style>
