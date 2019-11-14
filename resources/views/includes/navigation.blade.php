<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('home') }}" class="site_title">
                <img src="http://netlicensing.io/img/labs64-avatar-30x30.png" alt="{{ config('app.name') }}">
                <span>{{ __('views.sections.navigation.sub_header_0') }}</span>
            </a>
        </div>

        <div class="clearfix"></div>
        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Licensing Models</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            {{ __('views.sections.navigation.menu_0') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('try_and_buy') }}">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            {{ __('views.sections.navigation.menu_1') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('subscription') }}">
                            <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                            {{ __('views.sections.navigation.menu_2') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>Useful Links</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="https://go.netlicensing.io/console/v2/?lc=4b566c7e20&source=lmbox001" target="_blank">
                            <i class="fa fa-heartbeat" aria-hidden="true"></i>
                            Live demo
                        </a>
                    </li>
                    <li>
                        <a href="https://netlicensing.io/wiki/restful-api" target="_blank">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                            RESTful API
                        </a>
                    </li>
                    <li>
                        <a href="http://io.labs64.com/NetLicensing-API/" target="_blank">
                            <i class="fa fa-code" aria-hidden="true"></i>
                            API Test Center
                        </a>
                    </li>
                    <li>
                        <a href="http://netlicensing.io/licensing-models/" target="_blank">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            Licensing Models
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
