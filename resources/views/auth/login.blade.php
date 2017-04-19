@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height soda-login', 'html_class'=>'full-height'])

@section('head.title')
    <title>Login :: Soda CMS</title>
@endsection

@section('main-content')
    <form method="POST" class="soda-login__container">
        <div class="fadeInUp">
            {!! csrf_field() !!}
            <div class="soda-login__box">
                <div class="soda-login__logo">
                    @include(soda_cms_view_path('layouts.partials.header-image'))
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
                    <input type="text" name="email" placeholder="Email Address" value="{{ old('email') }}">
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

            {{--
            <div class="soda-login__pass-forgot">
                <a href="#">I forgot my password</a>
            </div>
            --}}
        </div>
    </form>
@endsection

@section('footer.js')
    @parent
    <script>
        $(function () {
            $(".soda-login__input input").on('focus', function () {
                $(this).closest('.soda-login__input').find(".soda-login__underline").css({
                    "width": "100%"
                });
            }).on('blur', function () {
                $(this).closest('.soda-login__input').find(".soda-login__underline").css({
                    "width": "0px"
                });
            });

            // AUTOFILL STYLING REMOVAL HACK
            var _retries = 0;
            var _interval = window.setInterval(function ()
            {
                var autofills;
                var terminateAutofillInterval = function() {
                    $('.soda-login__input input').jvFloat();
                    window.clearInterval(_interval); // stop polling
                }

                try {
                    autofills = $('.soda-login__input input:-webkit-autofill');
                } catch (error) {
                    try {
                        autofills = $('.soda-login__input input:autofill');
                    } catch (error) {
                        terminateAutofillInterval();
                    }
                }

                if (autofills.length > 0) {
                    var _clones = [];
                    autofills.each(function() {
                        var clone = $(this).clone(true, true);
                        $(this).after(clone).remove();
                        _clones.push(clone);
                    });

                    terminateAutofillInterval();

                    $.each(_clones, function(index, _clone) {
                        _clone.siblings('.placeHolder').addClass('active');
                    })

                } else if (_retries++ > 20) {
                    terminateAutofillInterval();
                }
            }, 50);
        });
    </script>
@stop
