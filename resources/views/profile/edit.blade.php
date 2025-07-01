@php
    $isAdmin = auth()->user()->is_admin;
@endphp

@if($isAdmin)
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        @include('profile.content')
    </x-app-layout>
@else
    @extends('layouts.app2')

    @section('content')
        @include('profile.content')
    @endsection
@endif
