@extends('layouts.app')

@section('title', $task->title)
@section('page-description', 'Task Details')

@section('content')
    @livewire('task-detail', ['task' => $task])
@endsection
