@extends('layouts.app')

@section('title', 'GMB Checklist')
@section('page-description', "Track monthly GMB / SEO optimization tasks per client")

@section('content')
    @livewire('gmb-checklist-grid')
@endsection
