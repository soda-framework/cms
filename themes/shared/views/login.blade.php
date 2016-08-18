@extends('soda_theme_hint::layouts.master')

@section('content')
    <div class='login'>
        <form class="" method="POST" action="/auth/login">
            {!! csrf_field() !!}
            <label for="email">Login</label>
            @if($errors->has('email'))
                <div class="error">
                    @foreach($errors->get('email') as $error)
                        {{$error}} <br />
                    @endforeach
                </div>
            @endif
            <input class="email" name='email' placeholder='please enter your email' type="text"/>
            <button>submit email</button>
        </form>
    </div>
@endsection
        
        
        