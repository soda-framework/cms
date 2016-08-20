@extends(config('soda.hint_path').'::layouts.bare', ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('main-content')
    <div class="dialog-box-wrapper">
        <div class="dialog-box">
            <div class="soda-logo-wrap">
                <div class="soda-logo">
                    <img src="/sodacms/sodacms/img/sodacms_droplime.png"/><br />
                    <img src="/sodacms/sodacms/img/sodacms_logowhite.png"/>
                </div>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('login-attempt') }}">
                {!! csrf_field() !!}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                    <div class="input-group">
                        <label class="input-group-addon" for="email"><i class="fa fa-user"></i></label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your email address...">
                    </div>

                    @if ($errors->has('email'))
                        <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                    <div class="input-group">
                        <label class="input-group-addon" for="password"><i class="fa fa-unlock-alt"></i></label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password...">
                    </div>

                    @if ($errors->has('password'))
                        <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group row">
                    <button type="submit" class="btn btn-dialog btn-block">
                        Login
                    </button>
                </div>
                {{--
                <p class="text-center">
                    <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                </p>
                --}}
            </form>
        </div>
    </div>
@endsection
