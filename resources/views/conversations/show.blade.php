@if (!empty($isCustomer) && $isCustomer)
    @extends('layouts.app2')
    @section('content')
        @include('conversations._show_content')
    @endsection
@else
    <x-app-layout>
        @include('conversations._show_content')
    </x-app-layout>
@endif
