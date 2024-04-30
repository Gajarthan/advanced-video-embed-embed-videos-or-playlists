<?php
/*
Search Panel markup
*/
$api_key = get_option('ave-yt-api');
if($api_key != ''){
?>
<form class="paypal_donate" style="float:right;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="Z7C7DNDD9VS3L">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>
<div class="ave_search">
  <form id="ave_search_form" action="<?php echo admin_url('admin-ajax.php');?>">
    <div class="input_in">
      <label for="s">Keyword</label>
      <input type="text" id="s" name="s" placeholder="search for video"/>
    </div>
    <div class="input_in">
      <label for="max">Number of videos(max:50)</label>
      <input type="text" id="max" name="max" value="25"/>
    </div>
    <div class="input_in">
      <label for="order">Order By</label>
      <select name="order">
        <option value="viewCount">Views Count</option>
        <option value="date">Date</option>
        <option value="rating">Rating</option>
        <option value="relevance">Relevance</option>
        <option value="title">Title(alphabet)</option>
        <option value="videoCount">Video Count</option>
      </select>
    </div>
    <input type="hidden" name="action" value="ave_loadVideos"/>
    <button type="submit" class="button primary" name="submit">Search</button><img id="results_loading" src="<?php echo plugins_url( '../img/loading.gif', __FILE__ );?>">
  </form>
  <div id="ave_search_results" class="section group ave_results">
  </div>
</div>

<div class="playlist_container">
  <div class="header_block">
    <h1 class="tag_short">Shortcode: </h1>
    <input type="text" name="playlist_tag_on" id="playlist_tag_on" value=""/>
    <input type="hidden" name="in_playlist" id="in_playlist" value=""/>
    <button class="button" style="float:left;margin-left:10px;" onclick="ave_generatePlaylist();">Generate</button>
    <button class="button primary" id="close_playlist_block" onclick="ave_playlistBlock();">X</button><span class="count_videos" data-c="0">0 Videos</span>
  </div>
  <div id="playlist_appender" class="section group grid_container">
  </div>
</div>
<?php } else {?>
  <div class="ave_search">
    API KEY NOT INSERTED - Please go to A.v.e Settings section and fill the api key.
  </div>
<?php }?>
