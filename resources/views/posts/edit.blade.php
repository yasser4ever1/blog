<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create new post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('components.messages')
            
            <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Post Information') }}
                    </h2>
            
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("Update form inputs to create the post.") }}
                    </p>
                </header>
            
                <form method="post" action="{{ route('posts.update', $post->id) }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="postForm" novalidate>
                    @csrf
                    
                    @method('PUT')
                    
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title)" required autofocus autocomplete="title" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>
            
                    <div>
                        <x-input-label for="slug" :value="__('Slug')" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $post->slug)" autofocus autocomplete="slug" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>
            
                    <div>
                        <x-input-label for="content" :value="__('Content')" />
                        <textarea id="content" name="content" class="mt-1 block w-full editor" required autocomplete="content">{{ old('content', $post->content) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>
            
                    <div>
                        <x-input-label for="excerpt" :value="__('Excerpt')" />
                        <textarea id="excerpt" name="excerpt" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required autocomplete="excerpt">{{ old('excerpt', $post->excerpt) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                    </div>
            
                    <div>
                        <a href="{{ $post->image }}" target="_blank">
                            <img class="card-img-top" src="{{ $post->thumbnail }}" alt="" title="{{ $post->title }}">
                        </a>

                        <x-input-label for="image" :value="__('Post Image')" />
                        <input type="file" name="image" class="custom-file-input" id="image">
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>
            
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Edit') }}</x-primary-button>
                    </div>
                </form>
                
                <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
                <script>tinymce.init({selector:'.editor'});</script>
                <style>.tox-notifications-container{display: none;}</style>
            </section>
        </div>
    </div>

    @section('script')
        <script>
            $(document).ready(function(){
                $('#title').on('change keyup paste', function () {
                    var slug = string_to_slug($("#title").val());

                    $("#slug").val(slug);
                });
            });

            function string_to_slug(str) {
                str = str.replace(/^\s+|\s+$/g, ''); // trim
                str = str.toLowerCase();
                
                // remove accents, swap ñ for n, etc
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to   = "aaaaeeeeiiiioooouuuunc------";
                for (var i=0, l=from.length ; i<l ; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }

                str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes

                return str;
            }
        </script>
    @endsection
</x-app-layout>
