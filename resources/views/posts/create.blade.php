@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Posts</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{url('/posts')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input name="title" type="text" class="form-control" id="title" placeholder="Title">
                        </div>
                        <div class="form-group">
                            <label for="content">Content:</label>
                            <textarea name="content" class="form-control" id="content" placeholder="type here..."></textarea>
                        </div>
                        <div class="checkbox">
                            <label><input name="published" type="checkbox" value="0"> Publish</label>
                        </div>
                        <button type="submit" class="btn btn-danger">Submit</button>
                        <a href="{{url('/posts')}}" class="btn btn-info"> Cancel </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
