/*jshint multistr: true */
jQuery(document).ready(function(){
  jQuery.post( ajaxurl, { action: "ave_loadVideos", max: "25",order:"viewCount",s:"" })
  .done(function( data ) {
    jQuery('#ave_search_results').html(data).slideDown();
    jQuery('#results_loading').hide();
  });
  jQuery('#ave_search_form').submit(function(e){
    var postData = jQuery(this).serializeArray();
    var formURL = jQuery(this).attr("action");
    jQuery('#results_loading').show();
    jQuery.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data)
        {
            jQuery('#ave_search_results').html(data).slideDown();
            jQuery('#results_loading').hide();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            alert('Something Went Wrong');
        }
    });
    e.preventDefault();
  });

});

function ave_createPopup(id,t,tl){
  var i = id;
  var mrkp = "<div class='overlay'></div>"+
  "<div class='modal_ave_container'>"+
    "<div class='modal'>"+
      "<div class='modal_header'>"+
        "<h1 class='title'>"+ tl +"</h1>"+
        "<a href='javascript:void(0);' onclick='ave_createPopup(\""+ i +"\",\"c\");' class='button primary'>Close</a>"+
      "</div>"+
      "<div class='modal_content'>"+
        "<div class='video_prev'>"+
          "<iframe width='560' height='315' src='https://www.youtube.com/embed/"+ i +"' frameborder='0' allowfullscreen></iframe>"+
          "<div class='post_buttons_ave'>"+
          "<button class='button button-primary' onclick='ave_publishPost(\""+i+"\",\""+tl+"\");'>Publish As Post</button>"+
          "</div>"+
        "</div>"+
        "<div class='details'>"+
          "<form id='form_ave_sub_"+i+"' class='form_ave_shortcode' data-vid='"+i+"' action='self();'>"+
            "<h1 class='title'>Generate Shortcode</h1>"+
            "<div class='mini_inputs'>"+
              "<label for='rel'>Related Videos</label>"+
              "<select id='rel' name='rel'>"+
                "<option value='Yes'>Yes</option>"+
                "<option value='No'>No</option>"+
              "</select>"+
            "</div>"+
              "<div class='mini_inputs'>"+
                "<label for='full'>Full Screen</label>"+
                "<select id='full' name='full'>"+
                  "<option value='Yes'>Yes</option>"+
                  "<option value='No'>No</option>"+
                "</select>"+
              "</div>"+
              "<div class='mini_inputs'>"+
                "<label for='controls'>Show Controls</label>"+
                "<select id='controls' name='controls'>"+
                  "<option value='Yes'>Yes</option>"+
                  "<option value='No'>No</option>"+
                "</select>"+
              "</div>"+
              "<button type='submit' name='submit' class='button primary'>Generate</button>"+
          "</form>"+
          "<div class='shortcode_input left'>"+
          "<input type='text' id='generated_"+i+"' style='display:none;' value=''/>"+
        "</div>"+
      "</div>"+
    "</div>"+
  "</div>";
  if(t == 'o'){
  jQuery('#ave_result_'+i).html(mrkp);
  jQuery('#ave_result_'+i).show();
  jQuery('#form_ave_sub_'+i).submit(function(e){
    var id = jQuery(this).data("vid");
    var rel = jQuery(this).find('#rel').val();
    var full = jQuery(this).find('#full').val();
    var controls = jQuery(this).find('#controls').val();'cv m,zi90'
    var f_id = 'i="'+i+'"';
    var f_e = '';
    if(rel === 'Yes'){
      var rel_v = ' rel="Yes"';
    } else {
      var rel_v = '';
    }
    if(full === 'Yes'){
      var full_v = ' full="Yes"';
    } else {
      var full_v = '';
    }
    if(controls === 'Yes'){
      var controls_v = ' controls="Yes"';
    } else {
      var controls_v = '';
    }
    var f_v = f_e.concat('[ave_yt ',f_id,rel_v,full_v,controls_v,']');
    jQuery('#generated_'+i).val(f_v).show();
    e.preventDefault();
  });
  } else {
  jQuery('#ave_result_'+i).hide();
  jQuery('#ave_result_'+i).empty();
  }
}

function ave_addPlaylist(i,t){
  var c_v = jQuery('.count_videos').data('c');
  var in_v = jQuery('#in_playlist').val();
  if(jQuery('#playlist_appender').find('#play_video_'+i).length){
    alert('Already Added To playlist');
  } else {
  var html = '<div id="play_video_'+i+'" data-vid="'+i+'" class="col span_1_of_5 in_playlist"  style="background:url(https://i.ytimg.com/vi/'+i+'/mqdefault.jpg) #fff no-repeat;">'+
    '<div class="title">'+t+'</div>'+
    '<div class="open_pop"><button class="button primary" onclick="ave_popup(\''+i+'\',\'o\',\''+t+'\');"><span class="dashicons dashicons-visibility" style="margin:2px;"></span>View</button><button style="float:right;" class="button blue" onclick="remove_from_playlist_ave(\''+i+'\');">Remove</button></div>'
  '</div>';
//  jQuery('#playlist_appender').append(html);
  jQuery(html).hide().appendTo("#playlist_appender").fadeIn(1000);
  jQuery('.playlist_container').show();
  jQuery('.count_videos').text(c_v+1+' Videos');
  jQuery('.count_videos').data('c',c_v+1);
  if(in_v === ''){
  jQuery('#in_playlist').val(i);
  } else {
    jQuery('#in_playlist').val(in_v+','+i);
  }
  }
}
function ave_playlistBlock(){
  var pl_h = jQuery('#playlist_appender').height();
  if (jQuery('.playlist_container').css('bottom') === '0px'){
    jQuery('.playlist_container').animate({bottom:'-'+pl_h+'px'});
    jQuery('#close_playlist_block').text('OPEN');
  } else {
    jQuery('.playlist_container').animate({bottom:'0px'});
    jQuery('#close_playlist_block').text('X');
  }
}
function ave_removePlaylit(i){
  jQuery('#play_video_'+i).remove();
  var c_v = jQuery('.count_videos').data('c');
  jQuery('.count_videos').text(c_v-1+' Videos');
  jQuery('.count_videos').data('c',c_v-1);

  if(jQuery('#playlist_appender').children().length < 0){
    playlist_block();
  }
}
function ave_generatePlaylist(){
  var vals = jQuery('#in_playlist').val();
  if(vals != ''){
  var code = '[ave_playlist ids="'+vals+'"]';
  } else {
    alert('Please add videos to playlist for shortcode');
  }
  jQuery('#playlist_tag_on').val(code).select();
}
function ave_publishPost(i,t){
  var short = jQuery('#generated_'+i).val();
  var thumb = 'https://i.ytimg.com/vi/'+i+'/mqdefault.jpg';
  if(short === ''){
    alert('Please generate shortcode first');
  } else {
    jQuery.post(ajaxurl,{action: "ave_getTypes"}).done(function(d){
      var select_p = "<div class='types_box_select'>"+d+"</div>";
      jQuery('.post_buttons_ave').append(select_p);
      jQuery('#post_type_ave_').change(function(){
       var type_val = jQuery(this).val();
       jQuery.post(ajaxurl,{action:"ave_getTerms",post_t:type_val}).done(function(d_t){
         jQuery('.post_buttons_ave > .types_box_select').append(d_t);
         jQuery('#post_term_ave_').change(function(){
           var term = jQuery(this).val();
           jQuery.post( ajaxurl, { action: "ave_publishPost",thumb:thumb,short:short,id:i,title:t,term:term,type:type_val})
             .done(function( data ) {
               if(data === 'ERROR'){
                 alert('Something went wrong');
               } else {
                 prompt("Post published",data);
               }
            });
         });
       });
      });
    });
  /*  jQuery.post( ajaxurl, { action: "ave_publishPost",thumb:thumb,short:short,id:i,title:t})
    .done(function( data ) {
      alert('Published As Post');
    });*/
  }
}
