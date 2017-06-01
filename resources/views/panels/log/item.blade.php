<tr>
    <td>
        @if($log->get('error'))
            <i class="fa fa-times-circle error" aria-hidden="true"></i>
        @elseif($log->get('warning'))
            <i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i>
        @else
            <i class="fa fa-check-circle success" aria-hidden="true"></i>
        @endif

        {{ $log->get('httpStatusCode') }}
    </td>
    <td>{{ $log->get('method') }}</td>
    <td>{{ $log->get('urlPart') }}</td>
    <td>{{ $log->get('requestHeaders',['Host'=>''])['Host'] }}</td>
    <td>
        <div class="dashboard-widget-content">
            <ul class="list-unstyled timeline widget">
                <li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title h2">
                                Method
                            </h2>
                            <div class="excerpt">
                                <span class="value">
                                    {{ $log->get('method') }}
                                 </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title h2">
                                Request Url
                            </h2>
                            <div class="excerpt">
                                 <span class="value">
                                      {{ $log->get('url') }}
                                 </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title h2">
                                Request Headers
                            </h2>
                            <div class="excerpt">
                                @foreach ($log->get('requestHeaders') as $key => $value)
                                    <div>
                                        <span class="key">{{ $key }}:</span>
                                        <span class="value">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title h2">
                                Parameters
                            </h2>
                            <div class="excerpt">
                                @if($log->get('data'))
                                    @foreach ($log->get('data',[]) as $key => $value)
                                        <div>
                                            <span class="key">{{ $key }}:</span>
                                            <span class="value">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </td>
    <td>
        <div class="dashboard-widget-content">
            <ul class="list-unstyled timeline widget">
                <li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title h2">
                                Response Headers
                            </h2>
                            <div class="excerpt">
                                @foreach ($log->get('responseHeaders') as $key => $value)
                                    <div>
                                        <span class="key">{{ $key }}:</span>
                                        <span class="value">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title h2">
                                Response
                            </h2>
                            <div class="excerpt">
                                <pre class="brush: xml">{{ $log->get('response') }}</pre>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </td>
</tr>