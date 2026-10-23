@extends('admin.base_template')

@section('content')
    <form action="?" method='POST'>
        @csrf

        @if($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <span>{{ $error }}</span>
            @endforeach
        </div>
        @endif

        <table>
            <tr>
                <td>Email:</td>
                <td><input class="form-control" name="email" value="{{ old('email', '') }}"></td>
            </tr>
            <tr>
                <td>Имя:</td>
                <td><input class="form-control" name="name" value="{{ old('name', '') }}"></td>
            </tr>
            <tr>
                <td>Пароль:</td>
                <td><input class="form-control" name="pass" type="password">
            </tr>
            <tr>
                <td>Повторение пароля:</td>
                <td><input class="form-control" name="pass_confirmation" type="password">
            </tr>
            <tr>
                <td></td>
                <td><input class="btn btn-primary" type="submit" value="Зарегистрироваться"></td>
            </tr>

        </table>
    </form>
@endsection
