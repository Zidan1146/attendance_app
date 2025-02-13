@extends('livewire.layout.app')

@section('content')
    {{ request()->route()->getName()}} &nbsp; {{  request()->route()->uri() }}
@endsection

