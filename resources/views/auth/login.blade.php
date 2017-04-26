@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height soda-login', 'html_class'=>'full-height'])

@section('head.title')
    <title>Login :: Soda CMS</title>
@endsection

@section('main-content')
    <div class="soda-login__logo">
        @include(soda_cms_view_path('layouts.partials.header-image'))
    </div>
    <form method="POST" class="soda-login__container">
        <div class="fadeInUp">
            {!! csrf_field() !!}
            <div class="soda-login__box">
                <div class="soda-login__errors">
                    @if ($errors->has('email'))
                        <span class="error">{{ $errors->first('email') }}</span>
                    @endif

                    @if ($errors->has('password'))
                        <span class="error">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="soda-login__input">
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" type="text" name="email" placeholder="Email Address" value="{{ old('email') }}">
                </div>

                <div class="soda-login__input">
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="soda-login__pass-forgot">
                <a href="#">Forgot password?</a>
            </div>
            <div class="soda-login__button-container">
                <button><span>Login</span></button>
            </div>
        </div>
    </form>
    <div class="wave-container">
       <canvas id="wave"></canvas>
    </div>
@endsection
@section('footer.js')
    @parent
    <script src="/soda/cms/js/wave.js"></script>
@endsection