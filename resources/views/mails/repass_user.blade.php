<!DOCTYPE html>
<html>
<body>
    <H2>Сброс пароля пользователя на сайте {{config('app.name')}}</H2>
    <a href="{{config('app.url')}}">{{config('app.url')}}</a><br>

    Ваш пароль был сброшен, новый пароль: <b>{{$pass}}</b> (незабудьте его поменять после входа в систему)

</body>
</html>
