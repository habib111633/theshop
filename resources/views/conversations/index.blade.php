@if (!empty($isCustomer) && $isCustomer)
    @extends('layouts.app2')
    @section('title', 'conversation')
    @section('content')
        @include('conversations._conversation_content')
    @endsection
@else
    <x-app-layout>
        @include('conversations._conversation_content')
    </x-app-layout>
@endif
