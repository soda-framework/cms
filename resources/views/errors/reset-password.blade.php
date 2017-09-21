@extends(soda_cms_view_path('layouts.bare'), ['body_class'=>'full-height login-screen', 'html_class'=>'full-height'])

@section('head.title')
    <title>@langtest('soda::forms.reset_password') :: Soda CMS</title>
@endsection

@section('main-content')
    <div class="modal fade in" style="display: block" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="resetPasswordLabel">@langtest('soda::phrases.reset_your_password')</h4>
                </div>

                <form method="POST" action="{{ route('soda.reset-weak-password') }}">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <p>@langtest('soda::phrases.reset_your_password_ext')</p>
                        @if ($errors->has('password'))
                            <p class="text-danger error">{{ $errors->first('password') }}</p>
                        @endif
                        @if ($errors->has('password_confirmation'))
                            <p class="text-danger error">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                        <div class="form-group">
                            <label>@langtest('soda::forms.password')</label>
                            <input type="password" name="password" class="form-control" placeholder="@langtest('soda::forms.new_password')">
                        </div>

                        <div class="form-group">
                            <label>@langtest('soda::forms.confirm_password')</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="@langtest('soda::forms.confirm_new_password')">
                        </div>                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('soda.logout') }}" class="btn btn-default">@langtest('soda::actions.logout')</a>
                        <button class="btn btn-primary">@langtest('soda::actions.continue')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade in"></div>
@stop
