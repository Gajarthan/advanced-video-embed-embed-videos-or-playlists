<?php
ini_set('display_errors',1);
if ( ! defined( 'ABSPATH' ) ) {
	die('not defined');
}

class AveShortcodes{

  function __construct(){
    $this->loadHooks();
  }
    function loadHooks(){
      add_shortcode('ave_yt',array($this,'ave_ytEmbed'));
			add_shortcode('ave_playlist',array($this,'ave_ytPlaylist'));
    }
    function ave_ytEmbed($atts){
      $yt = shortcode_atts(array('i'=>null,'rel'=>'0','controls'=>'1','width'=>'560','height'=>'315','full'=>'yes'),$atts);
      if($yt['full'] == 'no' || $yt['full'] == 'NO' || $yt['full'] == 'No'){
        $allow_full = '';
      } else {
        $allow_full = 'allowfullscreen';
      }
      if($yt['rel'] == 'no' || $yt['rel'] == 'NO' || $yt['rel'] == 'No'){
        $rel = 'rel=0&';
      } else {
        $rel = '';
      }
      if($yt['controls'] == 'no' || $yt['controls'] == 'NO' || $yt['controls'] == 'No'){
        $control = 'controls=0&';
      } else {
        $control = '';
      }
      $yt_html = '<iframe width="'.$yt['width'].'" height="'.$yt['height'].'" src="https://www.youtube.com/embed/'.$yt['i'].'?'.$rel.''.$control.'" frameborder="0"'.$allow_full.'></iframe>';
      return $yt_html;
    }
		function ave_ytPlaylist($atts){
			$data = shortcode_atts(array('ids'=>array()),$atts);
			if(!empty($data['ids'])){
				$ids = explode(',',$data['ids']);
				$id_c = count($ids);
				$f_id = '';
				$next_i = '';
				$i = 1;
				foreach($ids as $id){

					if($i == 1){
						$f_id = $id;
					} elseif($i != 1 && $i != $id_c){
						$next_i .= $id.',';
					} elseif($i == $id_c){
						$next_i .= $id;
					}
					$i++;
				}
			$playlist = '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$f_id.'?&playlist='.$next_i.'" frameborder="0" allowfullscreen></iframe>';
			}
			return $playlist;
		}
}
