@extends('admin.base_template')

@section('content')
    Вы авторизовались как {{ $user->name }} ({{ $user->email }})!
    <a href="/logout">Выйти</a>
@endsection
