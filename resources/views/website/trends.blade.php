@extends('layouts.frontend')

@section('content')
    <div class="site-section bg-primary-light" style="overflow: auto;">
        <div class="container" >
            {{-- @livewire('frontend.global-world-map') --}}
            <h1>Covid Data on Apple Trends World Wide</h1>
            @livewire('frontend.appletrends-search')
        </div>
    </div>
@endsection

