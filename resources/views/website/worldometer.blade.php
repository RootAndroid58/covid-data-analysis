@extends('layouts.frontend')

@section('content')
    <div class="site-section bg-primary-light">
        <div class="container" style="">
            {{-- <h1 class="text-center">Apple Trends in {{ $search }}</h1> --}}
            @livewire('frontend.global-world-map')
        </div>
        @livewire('frontend.worldo-meter-data-table')
    </div>
@endsection

