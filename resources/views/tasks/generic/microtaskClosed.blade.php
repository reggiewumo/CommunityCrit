@extends('layouts.app')

@section('content')
    @component('tasks.common.microtask')
        @slot('title')
            {{ $task->name }}
        @endslot

        @slot('text')
            {!! html_entity_decode($task->text) !!}
        @endslot
    @endcomponent
@endsection