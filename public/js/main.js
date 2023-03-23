//modal-auth
$('#header .auth-butt').click(function(){
	$('#modal-auth').show();
});
$('#modal-auth .cross,#modal-auth .over').click(function(){
	$('#modal-auth').hide();
});
//bootstrap tooltip
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
//scroll 
$('a.go_to').on('click', function(e){ 
	e.preventDefault();
	var _toGO = $(this).attr('href'); 
    if ($(_toGO).length != 0) {
    	$('html, body').animate({ scrollTop: $(_toGO).offset().top }, 750); 
     }
});

//menu slide 
$('#posts button').click(function (){
	$(this).parent().children('ul').toggleClass('post-hide');
});

// CLICK NEXT
function clickNext($el) {
  var block = $($el+' div.actives'),
      li    = $($el+' .post-nav li p.actives'),
      index = li.parent().index(),
      index_end = li.parent().parent().find('li').last().index();

  block.removeClass('actives').next('div.block').addClass('actives');
  li.removeClass('actives').parent().next().children().addClass('actives');

  if ( index == index_end){
    $($el+' div[data-id=\'0\']').addClass('actives');
    $($el+' ul li[data-id=\'0\']').children().addClass('actives');
  }
}

// CLICK PREV
function clickPrev($el){
  var block = $($el+' div.actives'),
      li    = $($el+' .post-nav li p.actives'),
      index = li.parent().index(),
      index_start = li.parent().parent().find('li').first().index(),
      index_end = li.parent().parent().find('li').last().index();

  block.removeClass('actives').prev('div.block').addClass('actives');
  li.removeClass('actives').parent().prev().children().addClass('actives');

  if ( index == index_start){
    $($el+' div[data-id=\''+index_end+'\']').addClass('actives');
    $($el+' ul li[data-id=\''+index_end+'\']').children().addClass('actives');
  }
}

// CLICK NAV CIRCLE
function clickCircle($el, child){  
  var block_$ = $($el+' div.block'),
        li_$  = $($el+' .post-nav ul li');

  li_$.each(function(){
    $(this).children().removeClass('actives');
  });
  $(child).children().addClass('actives');

  block_$.each(function(key, val){
    $(this).removeClass('actives');
      if( key == li_$.children('p.actives').parent().index() )
      {
        $(this).addClass('actives');
      }
  });
}
/*home page slider news slider*/
$('#main-post .post-nav .nav p.next').click( function(){
  clickNext('#main-post');
});
$('#main-post .post-nav .nav p.prev').click( function(){
  clickPrev('#main-post');
});
$('#main-post .post-nav ul li').click(function (){
  clickCircle('#main-post', $(this));
});

/* home page popular news slider*/
$('#popular-post .post-nav .nav p.next').click( function(){
  clickNext('#popular-post');
});
$('#popular-post .post-nav .nav p.prev').click( function(){
  clickPrev('#popular-post');
});
$('#popular-post .post-nav ul li').click(function (){
  clickCircle('#popular-post', $(this));
});

/* home page pappers news slider */
$('#papers .post-nav .nav p.next').click( function(){
  clickNext('#papers');
});
$('#papers .post-nav .nav p.prev').click( function(){
  clickPrev('#papers');
});
$('#papers .post-nav ul li').click(function (){
  clickCircle('#papers', $(this));
});

/* home page photos slider*/ 
$('#resors .post-nav .nav p.next').click( function(){
  clickNext('#resors');
});
$('#resors .post-nav .nav p.prev').click( function(){
  clickPrev('#resors');
});
$('#resors .post-nav ul li').click(function (){
  clickCircle('#resors', $(this));
});

/* livescore page news slider left-col*/
$('.archive .post-nav .nav p.next').click( function(){
  clickNext('.archive');
});
$('.archive .post-nav .nav p.prev').click( function(){
  clickPrev('.archive');
});
$('.archive .post-nav ul li').click(function (){
  clickCircle('.archive', $(this));
});

// hide comments 
$('#comments-block .comm-show i').click(function (){
  $(this).toggleClass('show');
    if ($(this).hasClass('show')) {
        $('#comments-block .comm-hide').show();
    }else {
      $('#comments-block .comm-hide').hide();
    }
})

// validate keyup textarea |send comment
$(document).ready(function (){
  $('.block-send textarea').keyup(function (){
    if ( $(this).val().length > 3 ) {
      $(this).parents('.block-send').next('.send').show();
    } else {
      $(this).parents('.block-send').next('.send').hide();
    }
  });
});
// validate keyup dynamic |reply comment
$(document).ready(function (){
  $('#comments').on('keyup', '.block-reply textarea' , function (){
    if ( $(this).val().length > 3 ) {
      $(this).parents('.write-comm').next('.reply').show();
    } else {
      $(this).parents('.write-comm').next('.reply').hide();
    }
  });
});

// trigger click for auth on /news/{id}
$('#comments-block .comm-show button').click(function (){
  $('#header .auth-butt').trigger('click');
});
$('#match #comments-block button').click(function (){
  $('#header .auth-butt').trigger('click');
});

$(document).ready(function (){
  var form_post  = $('#form-post form'),
      p_i = $('#preview-images-post');

    $('#post_type').change(function (){

    if ( $(this).val() == 3 ) {
      p_i.empty();
      form_post.children('.url').show();
      form_post.children('.photos').hide();

    }else if ( $(this).val() == 4 ){
      $('#post_url').val('');
      form_post.children('.photos').show();
      form_post.children('.url').hide();

    }else {
      $('#post_url').val('');
      p_i.empty();
      form_post.children('.url').hide();
      form_post.children('.photos').hide();
    }
  });
});

// Gallery photo
// CLICK NEXT
$('#news-main .nav p.next').click( function(){
  var block = $('#news-main div.actives'),
      index = block.index(),
      index_end = block.parent().find('.block').last().index();

block.removeClass('actives').next('div.block').addClass('actives');

if ( index == index_end){
  $('#news-main div[data-id=\'0\']').addClass('actives');
 }
});
// CLICK PREV
$('#news-main .nav p.prev').click( function(){
  var block = $('#news-main div.actives'),      
      index = block.index(),
      index_start = block.parent().find('.block').first().index();
      index_end = block.parent().find('.block').last().index();

  block.removeClass('actives').prev('div.block').addClass('actives');
   if ( index == index_start){
      $('#news-main div[data-id=\''+index_end+'\']').addClass('actives');
    }
});

// positons players
$(document).ready(function (){
  // home forward
  if ($('.home_forward').length == 1) {
    $('.home_forward').css('top', '220px');
  }else if ($('.home_forward').length == 2) {
    $('.home_forward:not(:first)').css('margin-top', '75px');
  }else if ($('.home_forward').length == 3) {
    $('.home_forward').css('top', '155px');
    var mar = 65;
    $('.home_forward').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=65;       
    });
  }
  // away forward
  if ($('.away_forward').length == 1) {
    $('.away_forward').css('top', '220px');
  }else if ($('.away_forward').length == 2) {
    $('.away_forward:not(:first)').css('margin-top', '75px');
  }else if ($('.away_forward').length == 3) {
    $('.away_forward').css('top', '155px');
    var mar = 65;
    $('.away_forward').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=65;       
    });
  }
  // central home defender
  if ( $('.home_central_defender, .home_defender').length == 2 ){
    $('.home_central_defender:not(:first), .home_defender:not(:first)').css('margin-top', '120px');
  }else if ( $('.home_central_defender, .home_defender').length == 3 ){
    $('.home_central_defender, .home_defender').css('top', '105px');
    var mar = 105;
    $('.home_central_defender, .home_defender').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=105;        
    });
  }else if ( $('.home_central_defender, .home_defender').length == 5 ){
    $('.home_central_defender, .home_defender').css('top', '85px');
    var mar = 65;
    $('.home_central_defender, .home_defender').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=65;       
    });
  }else {
    $('.home_central_defender, .home_defender').css('top', '115px');
    var mar = 72;
    $('.home_central_defender, .home_defender').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=72;       
    });
  }
  // central away defender
  if ( $('.away_central_defender, .away_defender').length == 2 ){
    $('.away_central_defender:not(:first), .away_defender:not(:first)').css('margin-top', '120px');
  }else if ( $('.away_central_defender, .away_defender').length == 3 ){
    $('.away_central_defender, .away_defender').css('top', '105px');
    var mar = 105;
    $('.away_central_defender, .away_defender').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=105;        
    });
  }else if ( $('.away_central_defender, .away_defender').length == 5 ){
    $('.away_central_defender, .away_defender').css('top', '85px');
    var mar = 65;
    $('.away_central_defender, .away_defender').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=65;       
    });
  }else {
    $('.away_central_defender, .away_defender').css('top', '115px');
    var mar = 72;
    $('.away_central_defender, .away_defender').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=72;       
    });
  }
  // home_central_midfielder 
  if ( $('.home_central_midfielder, .home_midfielder').length == 2 ){
    $('.home_central_midfielder:not(:first), .home_midfielder:not(:first)').css('margin-top', '170px');
  }else if ( $('.home_central_midfielder, .home_midfielder').length == 3 ){
    $('.home_central_midfielder, .home_midfielder').css('top', '75px');
    var mar = 142;
    $('.home_central_midfielder, .home_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=142;        
    });
  }else if ( $('.home_central_midfielder, .home_midfielder').length == 4 ){
    $('.home_central_midfielder, .home_midfielder').css('top', '75px');
    var mar = 95;
    $('.home_central_midfielder, .home_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=95;       
    });
  }else if ( $('.home_central_midfielder, .home_midfielder').length == 5 ){
    $('.home_central_midfielder, .home_midfielder').css('top', '70px');
    var mar = 70.5;
    $('.home_central_midfielder, .home_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=70.5;       
    });
  }else if ( $('.home_central_midfielder, .home_midfielder').length == 6 ){
    $('.home_central_midfielder, .home_midfielder').css('top', '60px');
    var mar = 62;
    $('.home_central_midfielder, .home_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=62;       
    });
  }

  //away_central_midfielder
  if ( $('.away_central_midfielder, .away_midfielder').length == 2 ){
    $('.away_central_midfielder:not(:first), .away_midfielder:not(:first)').css('margin-top', '170px');
  }else if ( $('.away_central_midfielder, .away_midfielder').length == 3 ){ 
     $('.away_central_midfielder, .away_midfielder').css('top', '75px');
    var mar = 142;
    $('.away_central_midfielder, .away_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=142;        
    });
  } else if ( $('.away_central_midfielder, .away_midfielder').length == 4 ){ 
     $('.away_central_midfielder, .away_midfielder').css('top', '75px');
    var mar = 95;
    $('.away_central_midfielder, .away_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=95;       
    });
  }else if ( $('.away_central_midfielder, .away_midfielder').length == 5 ){ 
     $('.away_central_midfielder, .away_midfielder').css('top', '70px');
    var mar = 70.5;
    $('.away_central_midfielder, .away_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=70.5;       
    });
  }else if ( $('.away_central_midfielder, .away_midfielder').length == 6 ){ 
     $('.away_central_midfielder, .away_midfielder').css('top', '60px');
    var mar = 62;
    $('.away_central_midfielder, .away_midfielder').each(function (key, val){
      if( key == 1 ) {
         return;
      }
      $(this).css('margin-top', mar+'px');
      mar +=62;       
    });
  }
});