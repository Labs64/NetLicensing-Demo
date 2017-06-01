<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--CSRF Token--}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{--Title and Meta--}}
        @meta

        {{--Common App Styles--}}
        {{ Html::style(mix('assets/css/default.css')) }}

        {{--Styles--}}
        @yield('styles')

        {{--Head--}}
        @yield('head')

    </head>
    <body class="nav-md">

        {{--Page--}}
        <div class="container body">
            <div class="main_container">
                @section('header')
                    @include('includes.navigation')
                    @include('includes.header')
                @show

                @yield('left-sidebar')
                <div class="right_col" role="main">
                    <div class="page-title">
                        <div class="title_left">
                            <h1 class="h3">@yield('title')</h1>
                        </div>
                        @if(Breadcrumbs::exists())
                            <div class="title_right">
                                <div class="pull-right">
                                    {!! Breadcrumbs::render() !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    @yield('content')
                </div>
                <footer>
                    @include('includes.footer')
                </footer>
            </div>
        </div>

        {{--Common Scripts--}}
        {{ Html::script(mix('assets/js/default.js')) }}

        {{--Laravel Js Variables--}}
        @tojs

        {{--Scripts--}}
        @yield('scripts')
    </body>
</html>