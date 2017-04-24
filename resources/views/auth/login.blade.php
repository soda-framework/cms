@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height soda-login', 'html_class'=>'full-height'])

@section('head.title')
    <title>Login :: Soda CMS</title>
@endsection

@section('main-content')
    <div class="logo-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 235.85 52.84">
            <path d="M14.5 50.23q.48-.39.94-.81c2.71-2.47 4.65-5.35 6.05-6.49a8.39 8.39 0 0 1 2.22-1.26c-.33-4.62-3.69-7-7.45-8.32-3.44-1.25-4.91-1.69-4.91-2.86 0-.59.44-1.54 2.64-1.54A11.78 11.78 0 0 1 20.08 31l3.08-7.69c-1.54-1.39-5.35-3.22-10.41-3.22C4.25 20.08 0 24.7 0 29.9c0 5.94 5.72 8.43 8.94 9.67 3 1.17 3.59 1.83 3.59 2.78S11.87 44 9.23 44a14.33 14.33 0 0 1-7.91-2.78H.44v8.94a16.18 16.18 0 0 0 5.89 2.32 10.65 10.65 0 0 0 8.17-2.25zm44.18-13.66c0-8.94-6.67-16.49-17-16.49s-17 7.55-17 16.49a17.51 17.51 0 0 0 .61 4.63A12.55 12.55 0 0 1 34 42.64c4.87 2.69 6.5 4.51 12.84 6.21a13.14 13.14 0 0 0 5.95.45 15.84 15.84 0 0 0 5.89-12.73zm-16.92 6.74c-3.22 0-5.64-2.64-5.64-6.82s2.27-6.89 5.5-6.89 5.64 2.93 5.64 7-2.26 6.71-5.5 6.71zm35.05 4.32c2.43-.91 5.62-2.67 10.45-2.57a20.46 20.46 0 0 1 6.68 1.29V0H82.72v23.74a9.61 9.61 0 0 0-7.84-3.66c-7.55 0-14.58 5.28-14.58 16.34 0 5.92 2 10.33 5.13 13.08 4.76 1.07 9.57-1.19 11.38-1.87zm.42-17.73c3.44 0 5.5 2.78 5.5 6.52S80.67 43 77.23 43s-5.5-2.93-5.5-6.38c0-3.79 2.12-6.72 5.5-6.72zm39.46 20.46a35.9 35.9 0 0 1 6.1-4.16 12.52 12.52 0 0 1 6.46-1.39V20.89h-11L118 23.6a9.48 9.48 0 0 0-7.84-3.52c-7.55 0-14.58 5.28-14.58 16.34 0 10.11 5.77 15.84 13.34 16.37a10.31 10.31 0 0 0 7.77-2.43zm-9.64-13.72c0-3.81 2.13-6.74 5.5-6.74s5.5 2.78 5.5 6.52S116 43 112.55 43s-5.5-2.91-5.5-6.36zM148.9 30a9.14 9.14 0 0 1 5.42 2l3.68-8.4c-2-2-5.64-3.52-10.11-3.52-10 0-16.64 7.25-16.64 16.34 0 9.75 6.23 16.42 16.27 16.42 3.15 0 7.18-1 9.31-2.71V40.6h-.73a9.61 9.61 0 0 1-6.82 2.49c-4.76 0-6.82-3.22-6.82-6.74A6.09 6.09 0 0 1 148.9 30zm51.41-9.92a14.61 14.61 0 0 0-11.21 5.42 8.08 8.08 0 0 0-8-5.42 13.38 13.38 0 0 0-10.33 5.06v-4.25h-11.2V52h11.21V33.42a7.87 7.87 0 0 1 5.13-2.56c2.49 0 3.44 1.61 3.44 4.84V52h11.21V33.42a7.72 7.72 0 0 1 5.2-2.56c2.42 0 3.37 1.69 3.37 4.84V52h11.21V33.12c.01-9.12-4.02-13.04-10.03-13.04zm28.06 13.26c-3.44-1.25-4.91-1.69-4.91-2.86 0-.59.44-1.54 2.64-1.54a11.78 11.78 0 0 1 6.08 2.06l3.08-7.69c-1.54-1.39-5.35-3.22-10.41-3.22-8.5 0-12.75 4.62-12.75 9.82 0 5.94 5.72 8.43 8.94 9.67 3 1.17 3.59 1.83 3.59 2.78S224 44 221.34 44a14.33 14.33 0 0 1-7.91-2.78h-.88v8.94a18.25 18.25 0 0 0 9.75 2.71c10.19 0 13.56-5.42 13.56-10.41s-3.53-7.72-7.49-9.12z" data-name="Layer 1"/>
        </svg>
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
    <script>
        var canvas;
        var context;
        var screenWidth;
        var screenHeight;

        var plotWidth = 720;

        var waves = [];

        var Wave = {
                create: function(waveColor, waveQuality, offsetA, amplitudeA, frequencyA, speedA, offsetB, amplitudeB, frequencyB, speedB, offsetY)
                {
                    var obj = Object.create(this);

                    obj.waveColor = waveColor;
                    obj.waveQuality = waveQuality;
                    obj.amplitudeA = amplitudeA;
                    obj.frequencyA = frequencyA;
                    obj.speedA = speedA;
                    obj.offsetA = offsetA;
                    obj.amplitudeB = amplitudeB;
                    obj.frequencyB = frequencyB;
                    obj.speedB = speedB;
                    obj.offsetB = offsetB;
                    obj.offsetY = offsetY;

                    return obj;
                },

                update: function()
                {
                    var i = 0;
                    var angleA = 0;
                    var angleB = 0;
                    var norm = 0;

                    //context.lineWidth = 0;
                    //context.strokeStyle = waveColor;
                    context.fillStyle = this.waveColor;
                    context.beginPath();

                    for(i; i < this.waveQuality; ++i)
                    {
                        norm = (i / this.waveQuality);
                        angleA = norm * this.frequencyA;
                        angleB = norm * this.frequencyB;

                        c = (screenHeight >> 1) + 200 - this.offsetY;
                        x = 0;
                        y = (Math.sin(angleA + this.offsetA * this.speedA) * this.amplitudeA) + (Math.sin(angleB + this.offsetB * this.speedB) * this.amplitudeB) + c;

                        if(i === 0) {
                            context.moveTo(x, y);
                        } else {
                            x = norm * plotWidth;
                            context.lineTo(x, y);
                        }
                    }

                    context.lineTo(x, canvas.height);
                    context.lineTo(0, canvas.height);
                    context.lineTo(0, c);

                    context.fill();
                    context.closePath();

                    this.offsetA += this.speedA;
                    this.offsetB += this.speedB;
                }
            };

        window.onload = function()
        {
            canvas = document.getElementById('wave');
            context = canvas.getContext('2d');

            window.onresize = function()
            {
                screenWidth = window.innerWidth;
                screenHeight = window.innerHeight;

                canvas.width = screenWidth;
                canvas.height = screenHeight;

                plotWidth = screenWidth;
            };

            window.onresize();

            waves.push(Wave.create('#8028e9', 200, 0, 58, 12, 0.08, 0, 40, 25, 0.08, 100));
            waves.push(Wave.create('#617eeb', 200, 250, 85, 10, 0.08, 310, 40, 20, 0.065, 265));

            loop();
        };

        function loop()
        {
            requestAnimationFrame(loop);

            context.fillStyle = '#f0f0f0';
            context.fillRect(0, 0, screenWidth, screenHeight);

            var i = waves.length - 1;

            for(i; i > -1; --i)
            {
                var wave = waves[i];
                wave.update();
            }
        }
    </script>
@endsection