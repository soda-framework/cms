@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('head.title')
    <title>Soda CMS | Login</title>
@endsection

@section('head.css')
    @parent
    <style>
        .box {
            position: relative;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-bottom: 0;
            border-radius: 10px 10px 0 0;
            padding: 50px;
            z-index: 5;
        }

        .box:before {
            content: "";
            height: 10px;
            border-radius: 10px;
            position: absolute;
            top: -11px;
            background: rgba(0, 0, 0, .6);
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            width: 95%;
            left: 2.5%;
            z-index: -1;
        }

        .input,
        .input input,
        .input .spin,
        .button,
        .button.login button,
        .pass-forgot a {
            transition: 300ms cubic-bezier(.4, 0, .2, 1);
            -webkit-transition: 300ms cubic-bezier(.4, 0, .2, 1);
            -ms-transition: 300ms cubic-bezier(.4, 0, .2, 1);
        }

        .input,
        .input input,
        .input .spin,
        .button,
        .button button {
            width: 100%;
        }

        .input,
        .button {
            margin-top: 30px;
            height: 70px;
        }

        .input,
        .input input,
        .button,
        .button button {
            position: relative;
        }

        .input input {
            height: 60px;
            top: 10px;
            border: none;
            background: transparent;
        }

        .input input,
        .button button {
            font-size: 24px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 300;
        }

        .input:before,
        .input .spin {
            width: 100%;
            height: 1px;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .input .spin {
            background: #0ad788;
            z-index: 4;
            width: 0;
        }

        .input:before {
            content: "";
            background: rgba(0, 0, 0, 0.1);
            z-index: 3;
        }

        .button.login {
            z-index: 2;
            width: 100%;
            height: auto;
            margin: 0px;
        }

        .button.login button {
            width: 100%;
            padding: 19px 0;
            background: rgba(0, 0, 0, 0.6);
            font-weight: 900;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-top: 2px transparent;
            border-radius: 0px 0px 10px 10px;
            cursor: pointer;
            z-index: 2;
        }

        .button.login button.active span {
            opacity: 0;
            transform: scale(0);
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
        }

        .button.login button:hover {
            background-color: #0ad788;
            border-color: #25b37c;
            border-top: 2px transparent;
            color: #fff;
        }

        .pass-forgot {
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .pass-forgot a {
            color: rgba(0, 0, 0, 0.35);
            font-size: 14px;
        }

        .pass-forgot a:hover, .pass-forgot a:active, .pass-forgot a:focus {
            color: rgba(0, 0, 0, 0.55);
        }

        body {
            background-image: url(http://sf.co.ua/2012/wallpaper-2077534.jpg);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .materialContainer {
            width: 100%;
            max-width: 540px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
        }

        input:focus {
            outline: none !important;
        }

        /*
 * Default jvFloat theme.
 * modify it as you wish!
 */

        .jvFloat {
            position: relative;
            display: inline;
            margin-top: 1em;
        }

        .jvFloat .placeHolder.required {
            color: red;
        }

        /* Start CSS3 Animations on supported browser */
        .jvFloat .placeHolder {
            position: absolute;
            top: -10px;
            left: 0;
            width: auto;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: normal;
            -webkit-transform: translate(0, 0);
            -moz-transform: translate(0, 0);
            -o-transform: translate(0, 0);
            -ms-transform: translate(0, 0);
            transform: translate(0, 0);
            -webkit-transition: -webkit-transform 150ms, opacity 100ms, visibility 100ms;
            transition: transform 150ms, opacity 100ms, visibility 100ms;
            opacity: 0;
            visibility: hidden;
        }

        /*Allows textarea floating placeholder to be positioned distinctly from the normal .placeHolder class
         * This is required when working with Multiline text areas
         */
        .jvFloat .placeHolder.textarea {
            /*top: 0px;*/
        }

        .jvFloat .placeHolder.active {
            display: block;
            visibility: visible;
            -webkit-transform: translate(0, -1em);
            -moz-transform: translate(0, -1em);
            -o-transform: translate(0, -1em);
            -ms-transform: translate(0, -1em);
            transform: translate(0, -1em);
            -webkit-transition: -webkit-transform 100ms, opacity 120ms, visibility 120ms;
            transition: transform 100ms, opacity 120ms, visibility 120ms;
            opacity: 1;
        }

        /* End CSS3 */

        /* Legacy browser */
        /*.jvFloat .placeHolder {
            position: absolute;
            top: -1em;
            left: 0;
            color: #0c61fc;
            font-size: .85em;
            font-weight: bold;
            opacity: 0;
            visibility: hidden;
        }
        .jvFloat .placeHolder.active {
            display: block;
            visibility: visible;
            opacity: 1;
        }*/
        /* End Legacy */

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            background: none !important;
            background-color: transparent !important;
            transition: background-color 5000s ease-in-out 0s !important;
            color: rgba(255, 255, 255, 0.8) !important;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.8) !important;
        }

        .soda-logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .soda-logo img {
            max-height: 55px;
        }

    </style>
@stop

@section('main-content')
    <form method="POST">
        {!! csrf_field() !!}
        <div class="materialContainer">
            <div class="box">
                <div class="soda-logo">
                    <img src="/soda/cms/img/sodacms_droplime.png">
                    <img src="/soda/cms/img/sodacms_logowhite.png">
                </div>

                <div class="input">
                    <input type="text" name="email" placeholder="Username">
                    <span class="spin"></span>
                </div>

                <div class="input">
                    <input type="password" name="password" placeholder="Password">
                    <span class="spin"></span>
                </div>
            </div>

            <div class="button login">
                <button><span>LOGIN</span></button>
            </div>

            <div class="pass-forgot">
                <a href="#">I forgot my password</a>
            </div>
        </div>
    </form>
@endsection

@section('footer.js')
    @parent
    <script>
        $(function () {
            $('input').jvFloat();

            $(".input input").on('focus', function () {
                $(this).closest('.input').find(".spin").css({
                    "width": "100%"
                });
            }).on('blur', function () {
                $(this).closest('.input').find(".spin").css({
                    "width": "0px"
                });
            });

            setTimeout(function () {
                try {
                    $('input:autofill').siblings('.placeHolder').addClass('active');
                } catch (error) {
                }
                try {
                    $('input:-webkit-autofill').siblings('.placeHolder').addClass('active');
                } catch (error) {
                }
            }, 500);
        });
    </script>
@stop
