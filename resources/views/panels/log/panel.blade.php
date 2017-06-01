<div class="x_panel">
    <div class="x_title">
        <h2>Log</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div role="tabpanel" data-example-id="togglable-tabs">
            <ul class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation">
                    <a href="#log_all" role="tab" data-toggle="tab" aria-expanded="false">
                        All
                    </a>
                </li>
                <li role="presentation" class="active">
                    <a href="#log_visible" role="tab" data-toggle="tab" aria-expanded="true">
                        Validation only
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade" id="log_all">
                    @include('panels.log.table',['log' => $log])
                </div>
                <div role="tabpanel" class="tab-pane fade active in" id="log_visible">
                    @include('panels.log.table',['log' => $log->where('hidden', false)])
                </div>
            </div>
        </div>
    </div>
</div>


