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
                <div class="row">
                    <div class="col-sm-6">
                        <a class="btn btn-dialog btn-block" href="{{ URL::previous() }}">Go back.</a>
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-dialog btn-block" href="{{ route('soda.logout') }}">Log out.</a>
                    </div>
                </div>
                <br/>
            </div>
        </div>
    </div>
@endsection
