@extends('admin.main')

@section('content')
<div class="container" id="admin-in">
    <div class="row">
        <div class="panel-body admin-login">
            <form class="form-horizontal" method="POST" action="{{ route('login_admin') }}">
              {{ csrf_field() }}

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="box-input">
                  <input id="login-email" type="text" class="form-control" name="email" value="{{ old('email') }}" required>
                  <label for="login-email" class="col-md-4 col-4 control-label">e-mail</label>
                </div>
                <div class="error">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="box-input">
                  <input id="login-password" type="password" class="form-control" name="password" required>
                  <label for="login-password" class="col-md-4 col-4 control-label">пароль</label>
                </div>
                <div class="error">
                      @if ($errors->has('password'))
                          <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                </div>
              </div>

              <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Запомнить
                    </label>
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Забыли пароль?
                        </a>
              </div>

              <div class="form-group button-log">
                <button type="submit" class="btn btn-primary">
                    <img src="{{ asset('images/log-in.png') }}" alt="">Войти
                </button>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection
