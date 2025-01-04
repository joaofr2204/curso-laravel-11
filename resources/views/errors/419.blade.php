@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message')
    <a href="{{ route('index') }}" class="btn btn-primary">{{ __('Page Expired') }}</a>
@endsection


