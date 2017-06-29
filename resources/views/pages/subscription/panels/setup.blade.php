<div class="x_panel">
    <div class="x_title">
        <h2>Validation</h2>
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
                        <a href="{{ route('subscription.regenerate') }}">
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
        <div role="tabpanel" data-example-id="togglable-tabs">
            <ul class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation" class="{{ !$errors->has('setup') ? 'active' : '' }}">
                    <a href="#playground" role="tab" data-toggle="tab" aria-expanded="false">
                        <i class="fa fa-gamepad" aria-hidden="true"></i>
                        Playground
                    </a>
                </li>
                <li role="presentation" class="{{ $errors->has('setup') ? 'active' : '' }}">
                    <a href="#setup" role="tab" data-toggle="tab" aria-expanded="true">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        Setup
                    </a>
                </li>
            </ul>

            {{ Form::open(['route' => 'subscription.validate', 'class'=>'form-horizontal form-label-left input_mask subscription']) }}
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ !$errors->has('setup') ? 'active in' : '' }}" id="playground">
                        <div class="ln_solid"></div>
                        <div class="row">
                            <div class="col-md-6">
                                @if($validationLog->get('validation.shop'))
                                    <a href="{{ $validationLog->get('validation.shop.shopURL')}}" class="btn btn-app">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        Shop
                                    </a>
                                @else
                                    <button class="btn btn-app" disabled="disabled">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        Shop
                                    </button>
                                @endif
                                <button class="btn btn-app validate" type="submit">
                                    <i class="fa fa-play" aria-hidden="true"></i>
                                    Validate
                                </button>
                            </div>
                        </div>
                        @if(!$validationLog->isEmpty())
                            <div class="ln_solid"></div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <h2>
                                        @if($validationLog->get('error') || $errors->has('validation'))
                                            <i class="fa fa-times error" aria-hidden="true"></i>
                                            <span class="error">Validation - Error</span>
                                        @elseif($validationLog->get('warning'))
                                            <i class="fa fa-exclamation warning" aria-hidden="true"></i>
                                            <span class="warning">Validation - Warning</span>
                                        @elseif(!$validationLog->get('valid'))
                                            <i class="fa fa-times error" aria-hidden="true"></i>
                                            <span class="error">Validation - Not valid</span>
                                        @else
                                            <i class="fa fa-check success" aria-hidden="true"></i>
                                            <span class="success">Validation - Success</span>
                                        @endif
                                    </h2>
                                    <div class="x_content">
                                        @if($errors->has('validation'))
                                            {{ $errors->first('validation') }}
                                        @elseif($validationLog->get('validation.result'))
                                            <div class="validation_result_content">
                                                @foreach ($validationLog->get('validation.result') as $key => $value)
                                                    <div>
                                                        <span class="key">{{ $key }}:</span>
                                                        <span class="value">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ $errors->has('setup') ? 'active in' : '' }}" id="setup">
                        <div class="ln_solid"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Connection settings</h4>
                            </div>
                            <div class="security_basic_auth">
                                <div class="col-md-6 {{$errors->has('username') ? 'has-error' : '' }}">
                                    <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                        <input name="username" class="form-control" type="text" placeholder="Username"
                                               value="{{  old('username', $setup->get('username')) }}">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary generate">
                                            Reset
                                        </button>
                                    </span>
                                    </div>
                                    @if($errors->has('username'))
                                        <div class="help-block">
                                            {{ $errors->first('username') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 {{$errors->has('password') ? 'has-error' : '' }}">
                                    <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                        <input name="password" class="form-control" type="password"
                                               placeholder="Password"
                                               value="{{  old('password', $setup->get('password')) }}">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary generate">
                                            Reset
                                        </button>
                                    </span>
                                    </div>
                                    @if($errors->has('password'))
                                        <div class="help-block">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <small class="description">
                                        Login name and password for the NetLicensing vendor account
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-12 security-toggle">
                                <div>
                                    {{ Form::checkbox('use_api_key', 1, old('use_api_key', $setup->get('use_api_key')), [
                                'id'=>'use_api_key',
                                'data-toggle'=>'toggle',
                                'data-width'=>'200',
                                'data-height'=>'34',
                                'data-on'=>'Validate using API Key',
                                'data-off'=>'Validate using Basic Auth',
                                'data-onstyle'=>'success',
                                'data-offstyle'=>'warning',
                                'autocomplete'=>'off'
                                ]) }}
                                </div>
                            </div>
                            <div class="security_api_key @if(old('use_api_key', $setup->get('use_api_key'))) active @endif">
                                <div class="col-md-12 {{$errors->has('api_key') ? 'has-error' : '' }}">
                                    <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                        <input name="api_key" class="form-control" type="text" placeholder="API Key"
                                               value="{{  old('api_key', $setup->get('api_key')) }}">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary generate">
                                            Reset
                                        </button>
                                    </span>
                                    </div>
                                    @if($errors->has('api_key'))
                                        <div class="help-block">
                                            {{ $errors->first('api_key') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <small class="description">
                                        <a href="https://www.labs64.de/confluence/x/-gHk" target="_blank">API Key</a> for the NetLicensing vendor account
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>

                        <div class="accordion" role="tablist">
                            <div class="panel">
                                <a href="#additionSetup" class="{{  !$errors->has('setup') ? 'collapsed' : '' }}"
                                   role="button" data-toggle="collapse" aria-expanded="false">
                                    <i class="fa fa-chevron" aria-hidden="true"></i><span class="h4">Additional settings</span>
                                </a>
                                <div class="panel-collapse collapse {{  $errors->has('setup') ? 'collapse in' : '' }}"
                                     role="tabpanel" id="additionSetup" aria-expanded="false">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Product</h4>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('product_number') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Number
                                                </span>
                                                <input name="product_number" class="form-control" type="text"
                                                       placeholder="Enter number"
                                                       value="{{  old('product_number', $setup->get('product_number')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('product_number'))
                                                <div class="help-block">
                                                    {{ $errors->first('product_number') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 {{$errors->has('product_name') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Name
                                                </span>
                                                <input name="product_name" class="form-control" type="text"
                                                       placeholder="Enter Name"
                                                       value="{{  old('product_name', $setup->get('product_name')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('product_name'))
                                                <div class="help-block">
                                                    {{ $errors->first('product_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Product Module</h4>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('product_module_number') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Number
                                                </span>
                                                <input name="product_module_number" class="form-control" type="text"
                                                       placeholder="Enter number"
                                                       value="{{  old('product_module_number', $setup->get('product_module_number')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                       <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('product_module_number'))
                                                <div class="help-block">
                                                    {{ $errors->first('product_module_number') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 {{$errors->has('product_module_name') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Name
                                                </span>
                                                <input name="product_module_name" class="form-control" type="text"
                                                       placeholder="Enter Name"
                                                       value="{{  old('product_module_name', $setup->get('product_module_name')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('product_module_name'))
                                                <div class="help-block">
                                                    {{ $errors->first('product_module_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>License Template (1 day)</h4>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('one_day_license_template_number') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Number
                                                </span>
                                                <input name="one_day_license_template_number" class="form-control"
                                                       type="text" placeholder="Enter number"
                                                       value="{{  old('one_day_license_template_number', $setup->get('one_day_license_template_number')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                         <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('one_day_license_template_number'))
                                                <div class="help-block">
                                                    {{ $errors->first('one_day_license_template_number') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 {{$errors->has('one_day_license_template_name') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Name
                                                </span>
                                                <input name="one_day_license_template_name" class="form-control"
                                                       type="text" placeholder="Enter Name"
                                                       value="{{  old('one_day_license_template_name', $setup->get('one_day_license_template_name')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('license_template_name'))
                                                <div class="help-block">
                                                    {{ $errors->first('license_template_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>License Template (10 days)</h4>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('ten_days_license_template_number') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Number
                                                </span>
                                                <input name="ten_days_license_template_number" class="form-control"
                                                       type="text" placeholder="Enter number"
                                                       value="{{  old('ten_days_license_template_number', $setup->get('ten_days_license_template_number')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                         <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('ten_days_license_template_number'))
                                                <div class="help-block">
                                                    {{ $errors->first('ten_days_license_template_number') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 {{$errors->has('ten_days_license_template_name') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Name
                                                </span>
                                                <input name="ten_days_license_template_name" class="form-control"
                                                       type="text" placeholder="Enter Name"
                                                       value="{{  old('ten_days_license_template_name', $setup->get('ten_days_license_template_name')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('ten_days_license_template_name'))
                                                <div class="help-block">
                                                    {{ $errors->first('ten_days_license_template_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Licensee</h4>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('licensee_number') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Number
                                                </span>
                                                <input name="licensee_number" class="form-control" type="text"
                                                       placeholder="Enter number"
                                                       value="{{  old('licensee_number', $setup->get('licensee_number')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('licensee_number'))
                                                <div class="help-block">
                                                    {{ $errors->first('licensee_number') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 {{$errors->has('licensee_name') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Name
                                                </span>
                                                <input name="licensee_name" class="form-control" type="text"
                                                       placeholder="Enter Name"
                                                       value="{{  old('licensee_name', $setup->get('licensee_name')) }}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary generate">
                                                         <i class="fa fa-refresh" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if($errors->has('licensee_number'))
                                                <div class="help-block">
                                                    {{ $errors->first('licensee_number') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
