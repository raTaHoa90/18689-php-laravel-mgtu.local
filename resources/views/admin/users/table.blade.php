@extends('admin.base_template')

@push('head')
    <script src='/js/admin/users.js'></script>
    <style>
        tbody.table-group-divider > tr {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <dialog id="addUserDlg" closedby="any">
        <form id="adduser" onsubmit="sendNewUser(); return false">
            {{-- @csrf --}}
            <table>
                <tr>
                    <td>Email</td>
                    <td><input class="form-control" name="email" type="email" autocomplete="off"> </td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td><input class="form-control" name="name" autocomplete="off"></td>
                </tr>
                <tr>
                    <td>Роль пользователя</td>
                    <td><select class="form-control" name="role">
                    @foreach ($roles as $roleID => $caption)
                        <option value="{{$roleID}}">{{$caption}}</option>
                    @endforeach
                    </select></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input class="form-control" type="submit" value="Создать пользователя"></td>
                </tr>
            </table>
        </form>
    </dialog>

    <section>
        {{-- request()->query() - получение текущих GET-переменных
        <a href="{{ route('users.table', request()->query() + ['test'=> 123]) }}"></a>
        <a href="{{ action('UserController@table', request()->query() + ['test'=> 123]) }}"></a>
        <a href="{{ url()->query('/admin/users', request()->query() + ['test'=> 123]) }}"></a>
        => https://18689-php-laravel-mgtu.local/admin/users?page=2&test=123
        --}}
        <form action="?" method="GET">
            <input name="page" type="hidden" value="{{request('page', 1)}}">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="input-group flex-nowrap">
                            <input name="search" value="{{request('search', '')}}" type="text" class="form-control" placeholder="Search..." aria-label="Username" aria-describedby="addon-wrapping">
                            <button type="submit" class="input-group-text" id="addon-wrapping"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <select name="roleID" onchange="this.parentNode.parentNode.parentNode.parentNode.submit()" class="form-control">
                            <option value="norole">- Не выбрано -</option>
                            @php($lastRoleSel = request('roleID', 'norole'))
                            @foreach ($roles as $roleID => $caption)
                                <option value="{{$roleID}}" @selected($roleID == $lastRoleSel) >{{$caption}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>
                        <i class="fa fa-cog"></i>
                        <button class="btn btn-success" style="float:right" onclick="openNewUserDlg()"><i class="fa fa-user-plus"></i></button>
                    </th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
            @foreach ($users as $userData)
                <tr onclick="location = '/admin/users/{{ $userData->name }}'">
                    <td>{{ $userData->id }}</td>
                    <td>{{ $userData->name }}</td>
                    <td>{{ $userData->email }}</td>
                    <td>@if($user->hasEditAdminUser($userData))
                        {{ $userData->roleName() }}
                    @else
                        <select onchange="setRoleUser({{ $userData->id }}, this.value)" onclick="return cancel(event);" class="form-control">
                        @foreach ($roles as $roleID => $caption)
                            <option value="{{$roleID}}"
                                @if($userData->group_role == $roleID) selected @endif
                            >{{$caption}}</option>
                        @endforeach
                        </select>
                    @endif
                    </td>
                    <td>@if(!$user->hasEditAdminUser($userData))
                        <a class="btn btn-danger" href="/admin/users/{{$userData->id}}/repass">Сбросить пароль</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </section>
@endsection
