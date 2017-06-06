@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12 licensing_model" href="{{ route('try_and_buy') }}">
                <div class="tile-stats">
                    <div class="icon">
                        <img src="http://netlicensing.io/img/licensing-model/licensing-model-try-and-buy.png">
                    </div>
                    <h3 class="count">
                        {{ __('views.home.header_0') }}
                    </h3>
                    <div class="sub_header">
                        {{ __('views.home.sub_header_0') }}
                    </div>
                    <p class="content">
                        {{ __('views.home.content_0') }}
                    </p>
                </div>
            </a>
            <a class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12 licensing_model" href="{{ route('subscription') }}">
                <div class="tile-stats">
                    <div class="icon">
                        <img src="http://netlicensing.io/img/licensing-model/licensing-model-subscription.png">
                    </div>
                    <h3 class="count">
                        {{ __('views.home.header_1') }}
                    </h3>
                    <div class="sub_header">
                        {{ __('views.home.sub_header_1') }}
                    </div>
                    <p class="content">
                        {{ __('views.home.content_1') }}
                    </p>
                </div>
            </a>
        </div>
    </div>
@endsection
