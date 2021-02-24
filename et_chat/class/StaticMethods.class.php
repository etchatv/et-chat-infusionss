<?php
/**
 * Class StaticMethods, contans only the simple methond for static use
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */

class StaticMethods{

	/**
	* Message filter, replaces smileys with images and "bad words"
	*
	* @param string $str, message text
	* @param Array  $sml, Smileys dataset
	* @return String
	*/
	static function filtering($str, $sml){
		
		//replace smileys
		for ($a=0; $a<count($sml); $a++){
                 $img = getimagesize("./".$sml[$a][1]);
                 $str = str_replace($sml[$a][0], "<img src=\"".$sml[$a][1]."\" ".$img[3].">", $str);
		}
		
		// create links from URIs
		if (!eregi(']http://', $str)) 
			$str = eregi_replace("(http://[^ )\r\n]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $str);
		else 
			$str = str_replace("http://www.youtube.com/watch?v=", "", $str);

		// Bad Word Filter
		
		if (file_exists("./bad_words.txt")){

			$inhalt_des_bad_word_files = file("./bad_words.txt");

			foreach($inhalt_des_bad_word_files as $bad_word_array){

				list($bad_word, $good_word) = explode(">", $bad_word_array);
				$bad_word = chop(trim($bad_word));
				$good_word = chop(trim($good_word));
				$str = eregi_replace($bad_word, $good_word , $str);
			}
		}	
		
		$video = '<object width="425" height="344"><param name="wmode" value="transparent" name="movie" value="http://www.youtube.com/v/$1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed wmode="transparent" src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="425" height="344"></embed></object>';
		
		if (eregi('\[img\]', $str) && eregi('\[/img\]', $str)){
			$image_path = preg_replace('/\[img\](.*?)\[\/img\]/', '$1', $str); 
				if (!empty($image_path))
					$str="<img src=\"$image_path\" style=\"max-width:500px;max-height:300px;\">";
		}
		$str = preg_replace('/\[video\](.*?)\[\/video\]/', $video, $str);  
		
		return $str;
	}
}