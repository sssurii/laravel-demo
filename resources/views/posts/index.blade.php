@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <a href="{{url('/posts/create')}}" class="btn btn-info pull-right"> Add Post </a>
                <div class="panel-heading">Posts</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Author
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Created on
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                                @php
                                $i = 1
                                @endphp
                                @if(!empty($posts))
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$post->title}}</td>
                                        <td>{{$post->Author->name}}</td>
                                        <td>{{$post->published? 'Y' : 'N'}}</td>
                                        <td>{{$post->created_at}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    
                                <td colspan="5">
                                    No record found
                                </td>
                                </tr>

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
