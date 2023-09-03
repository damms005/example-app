@extends('layouts.app')

@section('content')
  @foreach ($users as $user)
    <div>{{ $user->name }}</div>
  @endforeach
@endsection
