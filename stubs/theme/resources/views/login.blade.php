@extends('soda-example::layouts.master')

@section('content')
    <div class='login'>
        <form class="" method="POST" action="{{ route('soda-example.auth.login.post') }}">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="email">Login</label>
                @if($errors->has('email'))
                    <div class="error">
                        @foreach($errors->get('email') as $error)
                            {{ $error }} <br />
                        @endforeach
                    </div>
                @endif
                <input class="form-control" name="email" placeholder="Enter your email" type="text"/>
            </div>

                <div class="form-group">
                <label for="password">Login</label>
                @if($errors->has('password'))
                    <div class="error">
                        @foreach($errors->get('password') as $error)
                            {{ $error }} <br />
                        @endforeach
                    </div>
                @endif
                <input class="form-control" name='password' placeholder="Enter your password" type="password" />
            </div>

            <button class="btn btn-lg btn-info">Login</button>
        </form>
    </div>
@endsection
        
        
