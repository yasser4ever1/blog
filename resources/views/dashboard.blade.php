<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b>{{ __("Welcome back ").Auth::user()->name._(" you are viewing list of your posts.") }}</b>
                </div>
                
                <hr>

                <div class="container px-4 px-lg-5 mt-5 mb-5">
                    @if(count($posts) > 0)
                    <div class="row">
                        {!! $posts->links() !!}
                    </div>
                    @endif

                    @include('components.messages')

                    @if(count($posts) > 0)
                        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
                            @foreach($posts as $post)
                                <div class="col mb-5">
                                    <div class="card h-100">
                                        <!-- Product image-->
                                        <a href="{{ $post->image }}" target="_blank">
                                            <img class="card-img-top" src="{{ $post->thumbnail }}" alt="" title="{{ $post->title }}">
                                        </a>

                                        <!-- Product details-->
                                        <div class="card-body p-4">
                                            <div class="text-center">
                                                <!-- Product name-->
                                                <h5 class="fw-bolder">{{ $post->title }}</h5>
                                                <!-- Product price-->
                                                
                                                <small>Publish date : {{ $post->publish_date }}</small> <br>
                                                <small>Update date : {{ $post->update_date }}</small>
                                            </div>
                                        </div>
                                        
                                        <!-- Product actions-->
                                        <div class="card-footer p-4 pt-0 border-top-0">
                                            <div class="text-center d-flex justify-content-between mb-4">
                                                <a href="{{ route('posts.view', $post->slug) }}" class="btn btn-primary">View</a>
                                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-success">Edit</a>
                                                <a class="delete-post btn btn-warning" data-id="{{ $post->id }}" onclick="return confirm('Are you sure you want to move this post to trash?');">Trash</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Warning</h4>

                            <hr>

                            There is no posts yet. Start creating your <a href="{{ route('posts.create') }}" style="font-weight:bold;text-decoration:underline;">first post</a> now!
                        </div>
                    @endif
                    
                    @if(count($posts) > 0)
                    <div class="row">
                        <div class="col mb-5 d-flex">
                            {!! $posts->links() !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            $(document).ready (function() {
                $(".delete-post").on('click', function () {
                    var id = $(this).data("id");
                    var token = $("meta[name='csrf-token']").attr("content");

                    $.ajax({
                        url: "{{ url('posts') }}/" + id,
                        type: 'DELETE',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        context: this,
                        success: function (response){
                            if(response.status == 'success') {
                                $(this).parent().parent().parent().parent().closest("div").fadeOut();
                            }

                            $(".message").addClass('alert-' + response.status).text(response.message).fadeIn();
                        }
                    });
                });
            });
        </script>
    @endsection
</x-app-layout>