<?php

    $alertTypes = [
        'message' => 'info',
        'info'    => 'info',
        'error'   => 'danger',
        'danger'  => 'danger',
        'success' => 'success',
        'alert'   => 'warning',
        'warning' => 'warning',
    ];

?>

<div class="row">
    <div class="col-xs-12">
        @foreach($alertTypes as $message => $class)
            @if ($alert = session($message))
                <div class="alert alert-{{ $class }} alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ $alert }}
                </div>
            @endif
        @endforeach
        @if (count($errors))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
