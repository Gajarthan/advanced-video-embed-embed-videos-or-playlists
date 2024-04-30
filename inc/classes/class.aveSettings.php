<?php
if ( ! defined( 'ABSPATH' ) ) {
	die('not defined');
}

class AveAdmin{

  /*
  @$api_yt_key is youtube registered key under client's account.
  */
  private $api_yt_key;
  /*
  @$api_d_key is dailymotion registered key under client's account.
  */
  private $api_d_key;

  function __construct(){
    global $wpdb;
    $this->db = $wpdb;
    $this->api_yt_key = get_option('ave-yt-api');
    $this->ave_loadHooks();
  }

	/*
	Creating the hooks for the functions below
	*/
  function ave_loadHooks(){
    add_action('admin_menu', array($this,'ave_videoSearch'));
    add_action('wp_ajax_ave_loadVideos',array($this,'ave_loadVideos'));
    add_action('wp_ajax_nopriv_ave_loadVideos',array($this,'ave_loadVideos'));
  }
	/*
	Settings up admin menu for ave video search
	*/
  function ave_videoSearch(){
    add_menu_page("A.V.E VIDEO SEARCH",
    "A.V.E VIDEO SEARCH",
    "administrator",
    "ave_search",
    array($this,"ave_searchPanel"),
		plugins_url( '../img/LOGO_AVE.png', __FILE__ ));
  }
	/*
	loading the html markup for search panel.
	*/
  function ave_searchPanel(){
    require_once AVE_PLUGIN_DIR.'/inc/views/search_panel.php';
  }
  /*
  @keyword require the keyword to search for videos on two different platforms
  */
  function ave_loadVideos(){
    $keyword = $_REQUEST['s'];
		$max_ = $_REQUEST['max'];
		$order = $_REQUEST['order'];
		$params = array(
		 'q' => $keyword,
		 'maxResults' => $max_,
		 'order'=>$order,
	 );
    require_once AVE_PLUGIN_DIR.'/inc/youtube/Google/autoload.php';
    //require_once 'Google/Service/YouTube.php';

      /*
       * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
       * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
       * Please ensure that you have enabled the YouTube Data API for your project.
       */
      $DEVELOPER_KEY = $this->api_yt_key;

      $client = new Google_Client();
      $client->setDeveloperKey($DEVELOPER_KEY);

      // Define an object that will be used to make all API requests.
      $youtube = new Google_Service_YouTube($client);

      try {
        // Call the search.list method to retrieve results matching the specified
        // query term.
        $searchResponse = $youtube->search->listSearch('id,snippet',$params);

        $videos = '';
        $channels = '';
        $playlists = '';

        // Add each result to the appropriate list, and then display the lists of
        // matching videos, channels, and playlists.
        foreach ($searchResponse['items'] as $searchResult) {
          switch ($searchResult['id']['kind']) {
            case 'youtube#video':
						$title = str_replace("'","\'",$searchResult['snippet']['title']);
						?>
						<div class="col span_1_of_5 trigger_<?=$searchResult['id']['videoId'];?>" >
							<img src="<?php echo $searchResult['snippet']['thumbnails']['medium']['url'];?>" alt="<?=$searchResult['snippet']['title'];?>">
							<div class="open_pop"><button class="button primary" onclick="ave_createPopup('<?=$searchResult['id']['videoId'];?>','o','<?= $title;?>');"><span class="dashicons dashicons-visibility" style="margin:2px;"></span>View</button><button style="float:right;" class="button primary" onclick="ave_addPlaylist('<?=$searchResult["id"]["videoId"];?>','<?= $title;?>');"><span class="dashicons dashicons-plus" style="margin:2px;"></span>Playlist</button></div>
							<div class="title"><?=$searchResult['snippet']['title'];?></div>
						</div>
						<div class="popup_ave" id="ave_result_<?php echo   $searchResult['id']['videoId'];?>">

						</div>
						<?php
              break;
          }
        }
      } catch (Google_Service_Exception $e) {
				echo sprintf('<p>Api Error: <code>%s</code></p>',
					htmlspecialchars($e->getMessage()));
      } catch (Google_Exception $e) {
        echo sprintf('<p>An client error occurred: <code>%s</code></p>',
          htmlspecialchars($e->getMessage()));
      }
    exit();
  }
}
