@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height soda-login', 'html_class'=>'full-height'])

@section('head.title')
    <title>Soda CMS | Login</title>
@endsection

@section('main-content')
    <form method="POST" class="soda-login__container">
        {!! csrf_field() !!}
        <div class="soda-login__box">
            <div class="soda-login__logo">
                <img src="/soda/cms/img/sodacms_droplime.png">
                <img src="/soda/cms/img/sodacms_logowhite.png">
            </div>

            <div class="soda-login__errors">
                @if ($errors->has('email'))
                    <span class="error">{{ $errors->first('email') }}</span>
                @endif

                @if ($errors->has('password'))
                    <span class="error">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="soda-login__input">
                <input type="text" name="email" placeholder="Email Address">
                <span class="soda-login__underline"></span>
            </div>

            <div class="soda-login__input">
                <input type="password" name="password" placeholder="Password">
                <span class="soda-login__underline"></span>
            </div>
        </div>

        <div class="soda-login__button-container">
            <button><span>LOGIN</span></button>
        </div>

        <div class="soda-login__pass-forgot">
            <a href="#">I forgot my password</a>
        </div>
    </form>
@endsection

@section('footer.js')
    @parent
    <script>
        $(function () {
            $('.soda-login__input input').jvFloat();

            $(".soda-login__input input").on('focus', function () {
                $(this).closest('.soda-login__input').find(".soda-login__underline").css({
                    "width": "100%"
                });
            }).on('blur', function () {
                $(this).closest('.soda-login__input').find(".soda-login__underline").css({
                    "width": "0px"
                });
            });

            setTimeout(function () {
                try {
                    $('.soda-login__input input:autofill').siblings('.placeHolder').addClass('active');
                } catch (error) {
                }
                try {
                    $('.soda-login__input input:-webkit-autofill').siblings('.placeHolder').addClass('active');
                } catch (error) {
                }
            }, 500);
        });
    </script>
@stop
