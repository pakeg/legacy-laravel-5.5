@extends('layouts.main')

@section('content')
<div class="container" id="main-block">
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="panel panel-default" id="reset">
                <div class="panel-heading">Сброс пароля</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="box-input">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required>
                                <label for="email" class="col-md-4 col-4 control-label">E-mail</label>
                            </div>
                            <div>
                                @if ($errors->has('email'))
                                    <span class="error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="box-input">
                                <input id="password" type="password" class="form-control" name="password" required>
                                <label for="password" class="col-md-4 col-4 control-label">Пароль</label>
                            </div>
                            <div>
                                @if ($errors->has('password'))
                                    <span class="error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <div class="box-input">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <label for="password-confirm" class="col-md-4 col-4 control-label">Повторите пароль</label>                                
                            </div>
                            <div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="error">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    Сбросить пароль
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
