@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('head.title')
    <title>Soda CMS | Error</title>
@endsection

@section('main-content')
    <div class="dialog-box-wrapper">
        <div class="dialog-box">
            <div class="dialog-box-inner">
                <div class="permission-alert text-center">
                    <h1><i style="color:#FFC946" class="glyphicon glyphicon-alert"></i></h1>
                    <h4>You do not have permission to access this feature.</h4>
                </div>
                <br/>
                <a class="btn btn-dialog btn-block" href="{{ URL::previous() }}">Click here to go back.</a>
            </div>
        </div>
    </div>
@endsection
