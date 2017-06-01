<table class="footable table" data-show-toggle="true" data-expand-first="false">
    <thead>
    <tr>
        <th>Status</th>
        <th>Method</th>
        <th>Url</th>
        <th>Host</th>
        <th data-breakpoints="all" data-title="Request">Headers</th>
        <th data-breakpoints="all" data-title="Response">Response</th>
    </tr>
    </thead>
    <tbody>
        @each('panels.log.item', $logs, 'log');
    </tbody>
</table>