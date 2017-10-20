@section('frame-content')
    <html>
    <head>
        <style>
            body {
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                width: 100%;
                background-color: #3B6DDF;
                color: #FFF;
                height: 45px;
                margin: 0;
                padding: 10px 20px;
                overflow: hidden;
            }

            .alert__message {
                display: block;
                padding: 4px 0;
            }

            .alert__message a, .alert__message a:hover, .alert__message a:active, .alert__message a:visited, .alert__message a:focus {
                color: #FFF;
                text-decoration: none;
            }

            .alert__message a:hover, .alert__message a:active, .alert__message a:focus {
                text-decoration: underline;
            }

            .alert__dismiss {
                display: block;
                position: absolute;
                right: 10px;
                top: 0px;
                width: 35px;
                height: 45px;
                line-height: 42px;
                text-align: center;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <span class="alert__message">
            Viewing site in draft mode
            @if($currentPage = Soda::getCurrentPage())
            | This page is
            currently: {{ $currentPage->status == \Soda\Cms\Foundation\Constants::STATUS_LIVE ? 'Live' : 'Draft' }}
            @endif
        </span>
        <span class="alert__dismiss" onclick="closeAlert();">&times;</span>
        <script>
            var self = window.parent.document.getElementById('sodaDraftAlert');
            var closeAlert = function () {
                self.parentNode.removeChild(self);
            };
        </script>
    </body>
    </html>
@stop

<style>
    #sodaDraftAlert {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 9999;
        opacity: 0.8;
        background-color: #3B6DDF;
        height: 45px;
    }
</style>
<iframe id="sodaDraftAlert" class="soda__alert--drafting" frameborder="0" allowtransparency="1" scrolling="no" width="100%" srcdoc="{{ $__env->yieldContent('frame-content') }}"></iframe>
