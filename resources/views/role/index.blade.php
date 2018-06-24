@extends('layouts.default')
@section('content')
    <main class="app-content popup-role">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Danh sách các vai trò</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><a href="{{'home'}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item active"> Danh sách vai trò</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 custom-role">
                <div class="tile">

                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="rolesTable">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Vai trò</th>
                                <th>Tên hiển thị</th>
                                <th>Miêu tả</th>
                                <th>Số người</th>
                                <th>Quyền</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }} </td>
                                    <td>{{ $role->name }} </td>
                                    <td>{{ $role->display_name }} </td>
                                    <td>{{ $role->description }} </td>
                                    <td> {{ count($role->Users) }} </td>
                                    <td>
                                        @foreach($role->Permissions as $key => $permission)
{{--                                            @if($key == count($role->Permissions)-1)--}}
                                                <b>{{ $permission->display_name  }}</b>
                                            {{--@else--}}
                                                {{--<b>{{ $permission->display_name  }}</b>--}}
                                            {{--@endif--}}
                                        @endforeach
                                    </td>
                                    <td style="color:white">
                                        <a data-role-id="{{$role->id}}"
                                           data-role-edit-link="{{route('role-edit',$role->id)}}"
                                           data-role-update-link="{{route('role-update',$role->id)}}" class="btn btn-primary role-update">
                                            <i class="fa fa-lg fa-edit " aria-hidden="true"> </i>
                                        </a>
                                        @if(!count($role->Users)>0)
                                            <a data-role-id="{{$role->id}}"
                                               data-role-link="{{route('role-destroy',$role->id)}}" class="btn btn-danger role-destroy">
                                                <i class="fa fa-lg fa-trash-o " aria-hidden="true"> </i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary"
                                        id="btnAddrole" type="button"><i class="fa fa-pencil-square-o"
                                                                         aria-hidden="true"></i>Thêm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade " id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm mới vai trò</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="role-form">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Tên vai trò :</label>
                                        <input type="hidden" name="id" class="id" id="idRoleModal">
                                        <input class="form-control name" id="name" name="name" type="text" required
                                               aria-describedby="role">
                                        <p style="color:red; display: none;" class="name"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Tên hiển thị:</label>
                                        <input class="form-control display_name" id="display_name" name="display_name" type="text" required
                                               aria-describedby="permission">
                                        <p style="color:red; display: none;" class="display_name"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Miêu tả :</label>
                                        <input class="form-control description" id="description" name="description" type="text" required
                                               aria-describedby="description">
                                        <p style="color:red; display: none;" class="description"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">#STT</th>
                                            <th scope="col">Quyền</th>
                                            <th scope="col">Miêu tả</th>
                                            <th></th>
                                            <th scope="col">#STT</th>
                                            <th scope="col">Quyền</th>
                                            <th scope="col">Miêu tả</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @for($i = 0; $i< $permissions->count(); $i+=2)
                                            <tr>
                                                <th scope="row">{{ $i+1 }}</th>
                                                <td>{{ $permissions[$i]->display_name }}</td>
                                                <td>{{ $permissions[$i]->description }}</td>
                                                <td>
                                                    <div class="toggle">
                                                        <label>
                                                            <input type="checkbox" name="permission[]" value="{{$permissions[$i]->id}}" class="permission_{{ $permissions[$i]->id }}" id="{{$permissions[$i]->id}}"><span class="button-indecator"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                @isset($permissions[$i+1])
                                                <th scope="row">{{ $i+2 }}</th>
                                                <td>{{ $permissions[$i+1]->display_name }}</td>
                                                <td>{{ $permissions[$i+1]->description }}</td>
                                                <td>
                                                    <div class="toggle">
                                                        <label>
                                                            <input type="checkbox" name="permission[]" value="{{$permissions[$i+1]->id}}" class="permission_{{ $permissions[$i+1]->id }}" id="{{$permissions[$i+1]->id}}"><span class="button-indecator"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                @endisset
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="{{ route('role-store') }}" class="btn btn-primary"
                                    id="btn-save-role" name="btn-save-role" type="button">
                                Thêm
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('sub-javascript')
    <script src="{{ asset('js/web/role/index.js') }}"></script>
@endsection
