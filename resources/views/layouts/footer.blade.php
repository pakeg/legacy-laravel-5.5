    <footer id="footer">
				<div class="container">
					<div class="row">
						<div class="col-md-2 col-2">
              <div class="logo-name">
              	<p>Soccer</p><span>Times</span>
              </div>
              <div class="social">
              	<a href="#face"><img src="{{ asset('images/facebook.svg') }}" alt=""></a>
              	<a href="#tw"><img src="{{ asset('images/twitter.svg') }}" alt=""></a>
              	<a href="#gl"><img src="{{ asset('images/google.svg') }}" alt=""></a>
              	<a href="#vk"><img src="{{ asset('images/vk.svg') }}" alt=""></a>
              </div>
						</div>
						<div class="col-md-2 col-2">
							<div>Турниры</div>
              @foreach ($league as $league)
                <div>
                    <a href="{{route('cc-livescore', ['id'=>$league->id, 'name'=>str_slug($league->name)])}}?league={{$league->id}}">{{$league->name}}</a>
                </div>
              @endforeach
							<div class="scroll"><a href="#header" class="go_to">На верх <img src="{{ asset('images/scroll-arrow.jpg') }}" alt=""></a></div>
						</div>
						<div class="col-md-3 col-3">
              Мир
              <div class="cc">              
                  @foreach ($cc->chunk(4) as $chunk)
                    <div>
                      @foreach ($chunk as $cc)
                        <div class="country">
                            <a href="{{route('cc-livescore', ['id'=>$cc->id, 'name'=>str_slug($cc->name)])}}">{{$cc->name}}</a>
                        </div>
                      @endforeach
                    </div>
                  @endforeach
              </div>
            </div>
						<div class="col-md-5 col-5 footer-nav">
							<div>
								<nav>
	                <ul>
	                    <li><a class="{{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
	                    <li><a class="{{ Request::is('results') ? 'active' : '' }}" href="{{ url('results') }}">Результаты</a></li>
	                    <li><a class="{{ Request::is('livescore') ? 'active' : '' }}" href="{{ url('livescore') }}">Live Score</a></li>
                      <li><a class="{{ Request::is('authors') ? 'active' : '' }}" href="{{ url('authors') }}">Автора</a></li>
	                </ul>
                </nav>
							</div>
							<div>
								<div class="subs">
									<input type="text" name="sub_email" placeholder="Ваш e-mail">
									<button >Подписаться</button>
								</div>
							</div>
						</div>
					</div>
				</div>
    </footer>
    <div class="container copy-right">
      <div class="row"><span>Все права защищены &copy; {{ date('Y') }} год SoccerTimes</span></div>      
    </div>
		<div id="modal-auth">
			<div class="content">
				<div class="cross"><img src="{{ asset('images/cross.jpg') }}" alt=""></div>
				<div>
					<div class="tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li class="active">
								<a href="#login" class="active" aria-controls="login" role="tab" data-toggle="tab">ВХОД
								</a>								
							</li>
							<li>
								<a href="#register" aria-controls="register" role="tab" data-toggle="tab">РЕГИСТРАЦИЯ
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="login">
								<div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                      {{ csrf_field() }}

                      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="box-input">
                          <input id="login-email" type="text" class="form-control" name="email" value="{{ old('email') }}" required>
                          <label for="login-email" class="col-md-4 col-4 control-label">Ваш e-mail</label>
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
                          <label for="login-password" class="col-md-4 col-4 control-label">Ваш пароль</label>
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
                      <div>
                      	<p>Soccer</p><span>Times</span>
                      </div>
                    </form>
                </div>
							</div>
							<div role="tabpanel" class="tab-pane" id="register">
								<div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="box-input">
                            	<input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required>
                            	<label for="email" class="col-md-4 col-4 control-label">Ваш e-mail</label>
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
                            	<input id="password" type="password" class="form-control" name="password" required>
                            	<label for="password" class="col-md-4 col-4 control-label">Ваш пароль</label>                            	
                            </div>

                            <div class="error">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                        	<div class="box-input">
                        		<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            <label for="password-confirm" class="col-md-4 col-4 control-label">Повторите пароль</label> 
                          </div>
                        </div>

                        <div class="form-group checkbox">
                          <button type="submit" class="btn btn-primary">
                              <img src="{{ asset('images/man-user.svg') }}" alt="">Регистрация
                          </button>
                        </div>
                      <div>
                      	<p>Soccer</p><span>Times</span>
                      </div>
                    </form>
                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="over"></div>                
		</div>   
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <!-- include summernote js -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    <script src="{{ asset('js/main.js') }}"></script> 
</body>
</html>
