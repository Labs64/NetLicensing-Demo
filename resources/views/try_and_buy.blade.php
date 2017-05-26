@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Setup</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                            Generate All
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form class="form-horizontal form-label-left input_mask">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Connection settings</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                    <span class="input-group-addon">
                                       <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                        <input name="username" class="form-control" type="text" placeholder="Username"
                                               value="{{ $setup->get('username', old('username')) }}">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary">Generate</button>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                    <span class="input-group-addon">
                                       <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                        <input name="password" class="form-control" type="password"
                                               placeholder="Password"
                                               value="{{ $setup->get('password', old('password')) }}">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary">Generate</button>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <small class="description">
                                        Login name and password of the user sending the requests.
                                    </small>
                                </div>
                            </div>
                            <hr>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
