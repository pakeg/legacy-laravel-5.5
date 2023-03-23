@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
        	<div class="col-md-4 col-4">
        		@include('user._user')
        	</div>
        	<div class="col-md-8 col-8">
        		<div class="row">
        			<div id="form-post">
        				<form action="{{ action('User\Office\HomeController@create', [Auth()->user()->id]) }}" method="post" enctype="multipart/form-data">
        					{{ csrf_field() }}

                            <div class="form-group">
                                <img src="" id="preview-image-post" alt="">
                            </div>

        					<div class="form-group input-file">
        						<label for="post_image"></label><i class="fas fa-upload"></i>Изображение
        						<input type="file" name="post_image" id="post_image">
        					</div>

        					<div class="form-group">
        						<label for="post_name"></label>Заголовок
        						<input type="text" name="post_name" id="post_name" required="required">
        					</div>

        					<div class="form-group">
        						<label for="post_text"></label>Содержимое
        						<textarea  name="post_text" id="post_text" cols="30" rows="10"></textarea>
        					</div>

                            <div class="form-group">
                                <label for="post_type"></label>Тип
                                <select name="post_type" id="post_type">
                                    <option selected="selected" value="1">Статья</option>
                                    <option value="2">Новость</option>
                                    <option value="3">Видео</option>
                                    <option value="4">Галерея</option>
                                </select>
                            </div>

                            <div class="form-group url">
                                <label for="post_url"></label>Ссылка на видео
                                <input type="text" name="post_url" id="post_url">   
                            </div>

                            <div class="form-group photos input-file">
                                <label for="post_images"></label><i class="fas fa-upload"></i>Выберите изображения
                                <input type="file" name="post_images[]" id="post_images" multiple>                                   
                            </div>

                            <div class="form-group preview-images-post">
                                <div id="preview-images-post"></div>
                            </div>

        					<div class="form-group">
        						<label for="post_date"></label>Дата публикации
        						<input type="date" name="post_date" id="post_date">
        					</div>

        					<div class="form-group form-tags">
        						<label for="post_tag">Теги</label>
        						<input type="text" name="post_tag" id="post_tag">
        						<span>Для разделения тегов используйте ","</span>
        					</div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary edit">
                                    Создать
                                </button>
                            </div>

        				</form>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
      $('#post_text').summernote();          
    });
</script>
<script type="text/javascript">
    function image(evt) {
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
                var img = document.getElementById('preview-image-post');
                img.src = e.target.result;
            };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
    document.getElementById('post_image').addEventListener('change', image, false);

    function images(evt) {
    var files = evt.target.files; // FileList object
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {
        // Only process image files.
        if (!f.type.match('image.*')) {
            alert("Только картинка");
        }
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function (theFile) {
            return function (e) {
                // Render thumbnail.
                var div = document.createElement('div');
                div.innerHTML = ['<img class="thumb" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
                document.getElementById('preview-images-post').insertBefore(div, null);
            };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
    }
    document.getElementById('post_images').addEventListener('change', images, false);
</script>
@endsection
