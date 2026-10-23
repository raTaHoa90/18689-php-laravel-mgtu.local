<!DOCTYPE html>
<html>
<body>
    <H2>Вы зарегистрировались на сайте {{config('app.name')}}</H2>
    <a href="{{config('app.url')}}">{{config('app.url')}}</a><br>

    Вы указали свое имя: <b>{{$user->name}}</b><br>
    Ваш пароль для входа: <b>{{$pass}}</b>
</body>
</html>
