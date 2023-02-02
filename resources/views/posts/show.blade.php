@section('title', 'Post details : '.$post->title)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <div class="container px-4 px-lg-5 mt-3 mb-3">
                        <div class="row">
                            @include('components.messages')

                            <small>Publish date : {{ $post->publish_date }}</small>
                            <small>Update date : {{ $post->update_date }}</small>
                            
                            @if(Auth::user())
                            <div class="mb-2 mt-2">
                                @if($type == 'trash')
                                <a href="{{ route('posts.trash') }}" class="btn btn-success"><i class="fa fa-trash"></i> Go back</a>
                                <form class="d-inline" method="post" action="{{ route('posts.restore', ['id' => $post->id]) }}">
                                    @csrf
                        
                                    <button type="submit" class="btn btn-primary" title="Restore post"><i class="fa fa-undo"></i> Restore</button>
                                </form>
                                <a class="delete-post btn btn-danger" data-id="{{ $post->id }}" title="Delete post" onclick="return confirm('Are you sure you want to permanently remove this post?');"><i class="fa fa-times"></i> Delete post</a>
                                @else
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-success">Edit</a>
                                <a class="delete-post btn btn-warning" data-id="{{ $post->id }}" onclick="return confirm('Are you sure you want to move this post to trash?');"><i class="fa fa-trash"></i> Trash</a>
                                @endif
                            </div>
                            @endif
                            
                            <br>

                            @if($post->getAttributes()['image'])
                            <div class="col-md-3">
                                <div class="mt-3 mb-3">
                                    <img src="{{ $post->image }}" alt="" class="img-responsive" width="200">
                                </div>
                            </div>
                            @endif

                            @if($post->getAttributes()['image'])
                            <div class="col-md-9">
                            @endif

                            <div class="mt-3">
                                <h4 class="alert-heading">Post content</h4><hr>
                                
                                <p class="mt-1 text-sm text-gray-600">
                                    {!! $post->content !!}
                                </p>
                            </div>

                            @if($post->excerpt)
                            <div class="mt-3">
                                <h4 class="alert-heading">Post excerpt</h4><hr>
                            
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $post->excerpt }}
                                </p>
                            </div>
                            @endif

                            @if($post->getAttributes()['image'])
                            </div>
                            @endif
                        </div>
                    </div>
                </header>
            </section>
        </div>
    </div>
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b>{{ __("Comments") }}</b>
                </div>
                
                <hr>

                <div class="container px-4 px-lg-5 mt-5 mb-5">
                    @if(count($post->comments) > 0)
                        <div class="row">
                            @foreach($post->comments as $comment)
                                <div class="col-md-12 mb-5">
                                    <div class="alert alert-info">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder">{{ $comment->comment }}</h5><br><hr>
                                        <!-- Product price-->
                                        
                                        <small>Publish date : {{ $comment->date }}</small> <br>
                                        <small>Commented by : {{ $comment->author }}</small> <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">

                            There is no comments on this post yet. Start your first comment now!
                        </div>
                    @endif
                    
                    @include('components.messages')

                    <form method="post" action="{{ route('comments.store', $post->id) }}" class="mt-6 space-y-6" novalidate>
                        @csrf
                
                        @if(!Auth::check())
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="text" class="mt-1 block w-full" :value="old('email')" required autofocus autocomplete="email" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                        @endif

                        <div>
                            <x-input-label for="comment" :value="__('Comment')" />
                            <textarea id="comment" name="comment" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required autocomplete="comment">{{ old('comment') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Send') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(count($pending_approve) > 0)
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b>{{ __("Comments pending approve") }}</b>
                </div>
                
                <hr>

                <div class="container px-4 px-lg-5 mt-5 mb-5">
                    @if(count($pending_approve) > 0)
                        <div class="row">
                            @foreach($pending_approve as $comment)
                                <div class="col-md-12 mb-5">
                                    <div class="alert alert-warning">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder">{{ $comment->comment }}</h5><br><hr>
                                        <!-- Product price-->
                                        
                                        <small>Publish date : {{ $comment->date }}</small> <br>
                                        <small>Commented by : {{ $comment->author }}</small> <br>


                                        <form method="post" action="{{ route('comments.approve', $comment->id) }}" class="mt-6 space-y-6" novalidate>
                                            @csrf

                                            <button type="submit" class="btn btn-primary" title="Restore post"><i class="fa fa-undo"></i> Approve</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @section('script')
        <script>
            $(document).ready (function() {
                $(".delete-post").on('click', function () {
                    var id = $(this).data("id");
                    var token = $("meta[name='csrf-token']").attr("content");

                    @if($type == 'trash')
                    var url = "{{ url('posts/remove') }}/" + id;
                    @else
                    var url = "{{ url('posts') }}/" + id;
                    @endif

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            "id": id,
                            "type": "{{ $type }}",
                            "_token": token,
                        },
                        context: this,
                        success: function (response){
                            $(".message").addClass('alert-' + response.status).text(response.message).fadeIn();

                            if(response.status == 'success') {
                                window.location.href = "{{ route('dashboard') }}";
                            }
                        }
                    });
                });
            });
        </script>
    @endsection
</x-app-layout>
