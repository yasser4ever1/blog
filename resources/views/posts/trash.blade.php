<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trashed Posts') }}
        </h2>

        <form method="post" action="{{ route('posts.restore-all') }}">
            @csrf

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Restore All') }}</x-primary-button>
            </div>
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container px-4 px-lg-5 mt-5 mb-5">
                    @if(count($posts) > 0)
                    <div class="row">
                        {!! $posts->links() !!}
                    </div>
                    @endif

                    @include('components.messages')

                    @if(count($posts) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Publish date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>
                                            <a href="{{ $post->image }}" target="_blank">
                                                <img src="{{ $post->thumbnail }}" width="50" alt="" title="{{ $post->title }}">
                                            </a>
                                        </td>
                                        <td>{{ $post->publish_date }}</td>
                                        <td>
                                            <a href="{{ route('posts.view', array($post->id, 'trash')) }}" class="btn btn-primary btn-sm" title="View post"><i class="fa fa-eye"></i> View</a>
                                            <form class="d-inline" method="post" action="{{ route('posts.restore', ['id' => $post->id]) }}">
                                                @csrf
                                    
                                                <button type="submit" class="btn btn-success btn-sm" title="Restore post"><i class="fa fa-undo"></i> Restore</button>
                                            </form>
                                            <a class="delete-post btn btn-sm btn-danger" data-id="{{ $post->id }}" title="Delete post" onclick="return confirm('Are you sure you want to permanently remove this post?');" class="btn btn-danger"><i class="fa fa-times"></i> Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Warning</h4>

                            <hr>

                            There is no archived posts. Get back to <a href="{{ route('dashboard') }}" style="font-weight:bold;text-decoration:underline;">dashboard</a>.
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
                                $(this).parent().parent().fadeOut();
                            }

                            $(".message").addClass('alert-' + response.status).text(response.message).fadeIn();
                        }
                    });
                });
            });
        </script>
    @endsection
</x-app-layout>