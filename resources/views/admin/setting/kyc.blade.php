@extends('admin.layouts.master')

@section('master')
    <form action="" method="POST">
        @csrf

        @include('admin.partials.formData', [$formHeading])
    </form>
    
    <x-formGenerator />
@endsection
