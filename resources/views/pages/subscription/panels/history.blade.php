<div class="x_panel history">
    <div class="x_title">
        <h2>History</h2>
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
        <ul class="list-unstyled scroll-view">
            @foreach($histories as $history)
                <li class="media event {{ Request::get('history') == $history->get('id') ? 'active': '' }}">
                    <div class="pull-left border-aero history_thumb">
                        <a href="{{ route('try_and_buy', ['history' => $history->get('id')]) }}">
                            @if($history->get('validationLog') && $history->get('validationLog')->get('error') || $history->get('errors'))
                                <i class="fa fa-times error" aria-hidden="true"></i>
                            @elseif(!$history->get('validationLog')->get('valid'))
                                <i class="fa fa-times error" aria-hidden="true"></i>
                            @elseif($history->get('validationLog')->get('warning'))
                                <i class="fa fa-exclamation warning" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-check success" aria-hidden="true"></i>
                            @endif
                        </a>
                    </div>
                    <div class="media-body">
                        <a href="{{ route('subscription', ['history' => $history->get('id')]) }}">
                            @if($history->get('validationLog.error') || $history->get('errors'))
                                <span class="title error">
                                    Validation - Error
                                </span>
                            @elseif(!$history->get('validationLog')->get('valid'))
                                <span class="title error">
                                    Validation - Not valid
                                </span>
                            @elseif($history->get('validationLog')->get('warning'))
                                <span class="title warning">
                                    Validation - Warning
                                </span>
                            @else
                                <span class="title success">
                                    Validation - Success
                                </span>
                            @endif

                            <div class="history_content">
                                <p><strong>Licensee Number:</strong> {{ $history->get('setup.licensee_number') }}</p>
                                <p><strong>Product Number:</strong> {{ $history->get('setup.product_number') }}</p>
                                <p><small>{{ $history->get('date')->diffForHumans() }}</small></p>
                            </div>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
