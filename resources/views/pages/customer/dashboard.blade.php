@extends('layouts.base')

@section('title', 'Dashboard Customer')

@section('extra-fonts')

@endsection

@section('prerender-js')
<script>
window.props = {
    categories: @json($categories),
    projects: {
        all: @json($projectsAll),
        request: @json($projectsRequest),
        inProgress: @json($projectsInProgress),
        done: @json($projectsDone),
        rejected: @json($projectsRejected)
    },
    samples: {
        all: @json($samplesAll),
        request: @json($samplesRequest),
        inProgress: @json($samplesInProgress),
        done: @json($samplesDone),
        rejected: @json($samplesRejected)
    }
}
</script>
<script src="{{ asset('js/App.js') }}" defer></script>
@endsection

@section('extra-css')
@endsection

@section('content')
<div class="custom-container">
    <div class="custom-wrapper">
        <div id="customer-projects" ></div>
    </div>
</div>
@endsection

@section('extra-js')
@endsection
