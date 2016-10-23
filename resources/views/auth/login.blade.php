@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('head.title')
    <title>Soda CMS | Login</title>
@endsection

@section('main-content')
    <style>
        .dialog-box {
            transform: translateY(20px);
            opacity: 0;
            transition: opacity 1s ease, transform 1s ease;
        }

        .dialog-box-wrapper.loaded .dialog-box {
            transform: translateY(0px);
            opacity: 1;
        }
    </style>
    <div class="dialog-box-wrapper">
        <div class="dialog-box">
            <div class="dialog-box-inner">
                <div class="soda-logo-wrap">
                    <div class="soda-logo">
                        <img src="/soda/cms/img/sodacms_droplime.png"/><br/>
                        <img src="/soda/cms/img/sodacms_logowhite.png"/>
                    </div>
                </div>
                <form class="form-horizontal" role="form" method="POST" action="{{ route('soda.login-attempt') }}">
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                        <div class="input-group">
                            <label class="input-group-addon" for="email"><i class="fa fa-user"></i></label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"
                                   placeholder="Enter your email address...">
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
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="Enter your password...">
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
    </div>
@endsection

@section('footer.js')
    @parent
    <script>
        $(function () {
            $('.dialog-box-wrapper').addClass('loaded');
        });
    </script>
@stop
