<section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Post Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update form inputs to create the post.") }}
        </p>
    </header>

    <form method="post" action="{{ route('posts.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="postForm" novalidate>
        @csrf

        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <div>
            <x-input-label for="slug" :value="__('Slug')" />
            <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" autofocus autocomplete="slug" />
            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
        </div>

        <div>
            <x-input-label for="content" :value="__('Content')" />
            <textarea id="content" name="content" class="mt-1 block w-full editor" required autocomplete="content">{{ old('content') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('content')" />
        </div>

        <div>
            <x-input-label for="excerpt" :value="__('Excerpt')" />
            <textarea id="excerpt" name="excerpt" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required autocomplete="excerpt">{{ old('excerpt') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
        </div>

        <div>
            <x-input-label for="image" :value="__('Post Image')" />
            <input type="file" name="image" class="custom-file-input" id="image">
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
    
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector:'.editor'});</script>
    <style>.tox-notifications-container{display: none;}</style>
</section>