@extends('layouts.app')

@section('title', 'Idea #' . $idea->id)

@section('content')
<section id="idea-detail" class="masonry">
    <div id="idea" class="{{ $idea->img_url ? 'has-img' : '' }}">
        <div id="text">
            <h1>{{ $idea->name }}</h1>
            <h4>{{ $idea->text }}</h4>
        </div>
        @if ($idea->img_url)
            <div id="img">
                <img src="{{ $idea->img_url }}">
            </div>
            <div class="clearfix"></div>
        @endif
    </div>

    <div class="grid row" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": "#grid-sizer", "percentPosition": "true"}'>
        <div id="grid-sizer" class="col-md-3 col-sm-6"></div>
        <div class="grid-item col-md-12 col-sm-12">
            @component('ideas.common.comment', ['idea' => $idea])
            @endcomponent
        </div>
        @foreach ($commentsByTask as $comments)
            @if ($comments->first()->task && $comments->first()->task->id != 1)
                <!-- {{ $tsklen = strlen($comments->first()->task->type == 61 ? $questions->where('id',$comments->first()->ques_id)->first()->text : $comments->first()->task->name) }} -->
                <div class="grid-item{{ ($tsklen > 200) ? ' col-md-12 col-sm-12' : ' col-md-12 col-sm-12' }} task">
                    <ul class="list-group">
                        <li class="list-group-item dark">
                            <span class="name"><strong>{{ $comments->first()->task->name }}</strong></span>
                            <span class="pull-right">
                            @if ($comments->first()->task->type == 61)
                                {{ $questions->where('id',$comments->first()->ques_id)->first()->text }}
                                {{-- $comments->first()->question->text --}}
                            @else
                                {{ $comments->first()->task->text }}
                            @endif
                            </span>
                        </li>
                    </ul>
                </div> <!-- .grid-item -->
            @endif
            @foreach ($comments as $comment)
                <!-- {{ $commlen = strlen($comment->comment ? $comment->comment : $comment->text) }} -->
                <div class="grid-item{{ ($commlen > 400) ? ' col-md-12 col-sm-12' : (($commlen > 100) ? ' col-md-6 col-sm-6' : ' col-md-3 col-sm-6') }}">
                    <ul class="list-group">
                    @if ($comment->comment)
                        {{--<!-- @if ($comment->link)
                            <li class="list-group-item">
                                <strong>
                                    Submission: {{ $comment->link->type_str}}
                                </strong><br>
                                {!! $comment->link->text !!}
                            </li>
                        @endif -->--}}
                        <li class="list-group-item comments">
                            <span class="text">{!! $comment->comment !!}</span><br>
                            <em>
                                {{ $comment->user->fname }}, {!! dateForHumans($comment->created_at) !!}
                            </em>
                        </li>
                    @else
                        <li class="list-group-item comments">
                            <!-- {{ $comment->type_str }}<br> -->
                            <span class="text">{!! $comment->text !!}</span><br>
                            <em>
                                {{ $comment->user->fname }}, {{ dateForHumans($comment->created_at) }}
                            </em>
                        </li>
                    @endif
                    </ul>
                </div> <!-- .grid-item -->
            @endforeach
        @endforeach
    </div> <!-- .grid -->
    

</section>
@endsection