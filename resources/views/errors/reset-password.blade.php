@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('head.title')
    <title>Soda CMS | Reset Password</title>
@endsection

@section('main-content')
    <div class="dialog-box-wrapper">
        <div class="dialog-box">
            <div class="dialog-box-inner">
                <div class="permission-alert text-center">
                    <h1><i style="color:#0BD685" class="glyphicon glyphicon-info-sign"></i></h1>
                    <h4>Before you can continue, you must reset your password.</h4>
                    <form method="POST" action="{{ route('soda.reset-weak-password') }}">
                        {!! csrf_field() !!}
                        @if ($errors->has('password'))
                            <span class="text-danger error">{{ $errors->first('password') }}</span>
                        @endif
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger error">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                        <br />

                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="New password">
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                        </div>
                        <button class="btn btn-dialog btn-block">Continue</button><br />
                        Or <a href="{{ route('soda.logout') }}">Log out.</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
