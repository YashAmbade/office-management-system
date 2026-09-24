@extends('layouts.app')

@section('title', $client->name)
@section('page-description', 'Client profile and services')


@section('content')
    {{-- @livewire('task-generator', ['client' => $client]) --}}
    @livewire('client-detail', ['client' => $client])
@endsection
