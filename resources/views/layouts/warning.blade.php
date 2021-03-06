@extends('layouts.base')

@section('extra-fonts')

@endsection

@section('prerender-js')

@endsection

@section('extra-css')
@endsection

@section('content')
    <custom-page image="{{ asset($picture) }}" title="{{ $title }}" message="{{ $text }}" backUrl="{{ url()->previous() }}"></custom-page>
@endsection

@section('extra-js')
    <script src="{{ asset('js/customPage.js') }}"></script>
@endsection
