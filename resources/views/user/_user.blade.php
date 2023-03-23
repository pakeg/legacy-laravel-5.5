<div id="form-user">
	<div class="user-name">
		<img id="preview-image" src="{{ $thumb }}" alt="">
			@if ($errors->has('image'))
        <span class="help-block">
            <strong>{{ $errors->first('image') }}</strong>
        </span>
      @endif
		<h3><input data-toggle="tooltip" data-placement="top" title="Изменить имя" type="text" name="name" readonly="readonly" value="{{ $user->name }}"></h3>
			@if ($errors->has('name'))
          <span class="help-block">
              <strong>{{ $errors->first('name') }}</strong>
          </span>
      @endif
	</div>
	<div class="user-buttons">
		<div class="file-upload" id="file-upload">
	    <label>
	      <input type="file" name="image" id="uploadimage">
	      <span><i class="fas fa-upload"></i>Сменить фото</span>
	    </label>
	  </div>
	  <div>
	  	<button id="comments"><a class="{{ Request::is('user-pannel/*/comments') ? 'active' : '' }}" href="{{ action('User\Office\HomeController@listComment', [Auth::user()->name]) }}"><i class="fas fa-comments"></i>Последние комментарии</a></button>
		</div>
	  <div id="posts">
	  	<button class="{{ Request::is('user-pannel/*/list') ? 'active' : '' }}"><i class="fas fa-edit"></i>Статьи</button>
	  	<ul class="post-hide">
	  		<li><a href="{{ action('User\Office\HomeController@create', [Auth::user()->name]) }}">Написать статью</a></li>
	  		<li><a href="{{ action('User\Office\HomeController@list', [Auth::user()->name]) }}">Список статей</a></li>
	  	</ul>
	  </div>        				
	</div>
</div>