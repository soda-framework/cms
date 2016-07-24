@extends(config('soda.hint_path').'::layouts.app')

@section('main-content')

    <div class="main-content row">
        <div class="col-xs-6 col-xs-offset-3">

            <div class="main-content-inner">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default login-box">
                            <h1 class="panel-heading">Login</h1>
                            <div class="panel-body">
                                <form class="form-horizontal" role="form" method="POST"
                                      action="{{ route('login-attempt') }}">
                                    {!! csrf_field() !!}

                                    <div class="form-group row">
                                        <label class="col-md-4 control-label">E-Mail Address</label>

                                        <div class="col-md-8">
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ old('email') }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                                        <label class="col-md-4 control-label">Password</label>

                                        <div class="col-md-8">
                                            <input type="password" class="form-control" name="password">

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember"> Remember Me
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary pull-right">
                                                <i class="fa fa-btn fa-sign-in"></i> Login
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 col-md-offset-4">
                                            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your
                                                Password?</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
