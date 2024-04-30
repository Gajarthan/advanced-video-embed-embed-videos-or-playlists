<?php
/*
Loading the markup
*/
if (isset($_GET['settings-updated'])){
  ?>
<div id="message" class="updated">
  <p><strong><?php _e('Settings saved.'); ?></strong></p>
</div>
<?php
} ?>
<form method="post" action="options.php">
<?php
/*
Loading registered settings
*/
settings_fields('ave-opts');

$api = get_option('ave-yt-api');
if($api == ''){
  $api = 'AIzaSyDOHJkmchgk4rE7_XuiEGalQnFnSf15hfs';
} else{
  $api = $api;
}
?>
<div class="ave_settings">
  <h1 class="title">A.V.E SETTINGS</h1>
  <div class="input_cont">
    <label for="yt_api">Youtube Api Key</label>
    <input type="text" id="yt_api" name="ave-yt-api" value="<?php echo $api;?>"/>
  </div>
  <button type="submit" class="button primary" name="submit">Save</button>
  <p class="help_b">
  The Above key which is already filled in has limited quota for video search. To create your own api key follow the video below:<br>
  <iframe width="560" height="315" src="https://www.youtube.com/embed/Im69kzhpR3I" frameborder="0" allowfullscreen></iframe>
  <p>
  </div>
</form>
