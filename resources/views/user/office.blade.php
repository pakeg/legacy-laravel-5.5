@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
        	<div class="col-md-4 col-4">
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
							  	<button id="comments"><a href="{{ action('User\Office\HomeController@listComment', [Auth::user()->name]) }}"><i class="fas fa-comments"></i>Последние комментарии</a></button>
								</div>
							  <div id="posts">
							  	<button><i class="fas fa-edit"></i>Статьи</button>
							  	<ul class="post-hide">
							  		<li><a href="{{ action('User\Office\HomeController@create', [Auth::user()->name]) }}">Написать статью</a></li>
							  		<li><a href="{{ action('User\Office\HomeController@list', [Auth::user()->name]) }}">Список статей</a></li>
							  	</ul>
							  </div>        				
        			</div>
        		</div>
        	</div>
        	<div class="col-md-8 col-8">
        		<div id="user-stats">
        			<div>Новостей: &#8195;{{$count_news}}</div>
        			<div>Статей: &#8195;{{$count_articles}}</div>
        		</div>
        	</div>
        </div>
    </div>
    <script type="text/javascript">
    	$('#form-user .user-name input').click(function () {
    		$(this).removeAttr('readonly').css('cursor','auto');
    		localStorage.setItem('name_old', $(this).val());
    	});

    	$('#form-user .user-name input, #file-upload input').change(function (){
  			$(this).attr('readonly','readonly').css('cursor','pointer');

  			var name 		  = $('#form-user .user-name input').val(),
  					name_old  = localStorage.getItem('name_old'),
 						image 		= $("#uploadimage"),
        		file      = new FormData();

    				file.append('image', image.prop('files')[0]);
    				file.append('name', name);

			  			if ( (name.trim() != name_old.trim()) || ( image.val() != '' ) ) {
								$.ajax({
									 headers: {
								    'X-CSRF-TOKEN': '{{ csrf_token() }}'
								  },
									type:'post',
								  url: '/user-pannel/{{ $user->id }}',
								  data: file,
								  processData: false, 
			        		contentType: false,
								  success: function(json){
								  	$('#header .loged p').html(json);
								  	$('#header .loged .dropdown-menu a').first().attr('href', '{{ url('/user-pannel') }}/' + json);
								  },
								  error: function(xhr, ajaxOptions, thrownError) {
					          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					          $('.page-header').after('<div id="import" class="container-fluid">'+ thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText +'</div>');
					      	}	
								});
							}
    	});
    </script>
    <script type="text/javascript">
			function handleFileSelect(evt) {
			    var file = evt.target.files; // FileList object
			    var f = file[0];
			    // Only process image files.
			    if (!f.type.match('image.*')) {
			        alert("Только картинка");
			    }
			    var reader = new FileReader();
			    // Closure to capture the file information.
			    reader.onload = (function(theFile) {
			        return function(e) {
			            // Render thumbnail.
			            var img = document.getElementById('preview-image');
			            img.src = e.target.result;
			        };
			    })(f);
			    // Read in the image file as a data URL.
			    reader.readAsDataURL(f);
			}
			document.getElementById('uploadimage').addEventListener('change', handleFileSelect, false);
		</script>
@endsection