@extends('layouts.app')

@section('title', 'Dev - Idea Select')

@section('content')
    <div id="main-menu">
        <h1>Do An Activity</h1>
        <p>Please select an option below to begin contributing.</p>

        <p>You can select an idea someone else submitted to provide feedback about that idea. If you think of a new idea, you can submit it for others to work on. After you have completed a few tasks, please consider taking our exit survey.</p>

        <h2>Pick an Idea to Work On</h2>
        <div class="row">
            @foreach ($ideas as $idea)
                <div class="col-sm-6 col-md-4">
                    <a class="panel-link" href="{{ action( 'TaskController@showRandomTask', $idea->id) }}">
                        <div class="panel panel-default">
                            <div class="panel-body lg">
                                @if ($idea->name)
                                    {{$idea->name}}
                                @endif
                            </div>
                        </div> <!-- .panel -->
                    </a>
                </div> <!-- .col -->
            @endforeach
        </div>

        <h2>Have an idea?</h2>
        <a class="btn btn-primary" href="{{ route( 'submit-idea') }}">
            Submit a New Idea
        </a>

        <h2>All done?</h2>
        <a class="btn btn-primary" href="{{ route( 'exit') }}">
            Go to Exit Survey
        </a>
    </div>
@endsection
