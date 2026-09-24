@extends('layouts.app')

@section('title', 'Tasks')
@section('page-description', 'Manage and track your team\'s work')

@section('content')
    @livewire('task-board')
@endsection
