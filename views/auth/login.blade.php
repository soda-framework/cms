@extends(config('soda.hint_path').'::layouts.bare', ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('main-content')
    <div class="login-box-wrapper">
        <div class="login-box">
            <div class="soda-logo-wrap">
                <div class="soda-logo">
                    <img src="/sodacms/sodacms/img/sodacms_droplime.png"/><br />
                    <img src="/sodacms/sodacms/img/sodacms_logowhite.png"/>
                </div>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('login-attempt') }}">
                {!! csrf_field() !!}

                <div class="form-group row">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter your email address...">
                    </div>

                    @if ($errors->has('email'))
                        <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-unlock-alt"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password...">
                    </div>

                    @if ($errors->has('password'))
                        <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group row">
                    <button type="submit" class="btn btn-login btn-block">
                        Login
                    </button>
                </div>
                <p class="text-center">
                    <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                </p>
            </form>
        </div>
    </div>
@endsection
