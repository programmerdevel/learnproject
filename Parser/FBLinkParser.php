<?php
/**
 * Description of FBLinkParser
 *
 * @author hardik
 */
class FBLinkParser {
  
  const SOURCE_PATH = 'source/fb.html';
  
  const SERIALIZE_PATH = 'serialize/data.txt';
  
  private $page_content;
  
  private $records;
  
  public function __construct() {
    $this->set_page_content();
    $this->get_records();
  }
  
  private function get_records() {
    $fb_link = '';
    if(preg_match_all('@<a href=\'(/pagetracker/.*?)\'>(.*?)</a>@msi', $this->page_content, $matches, PREG_SET_ORDER)){
      foreach($matches as $match){
        $url = 'http://www.varsityoutreach.com'.$match[1];
        $content = file_get_contents($url);
        if(preg_match('@<a href="(http://www.facebook.com/profile.php.*?)">@msi', $content, $a_match)){
          $fb_link = $a_match[1];
        }
        $this->records[] = array(
            'name' => trim(strip_tags($match[2])),
            'link' => $fb_link
        );
      }
    }
    $this->save_serialize_file();
  }

  private function set_page_content() {
    $this->page_content = file_get_contents(self::SOURCE_PATH);
  }
  
  private function save_serialize_file() {
    $serialized_data = serialize($this->records);

    $fh = fopen(self::SERIALIZE_PATH, 'w+');
    fwrite($fh, $serialized_data);
    fclose($fh);
  }
}

$ins = new FBLinkParser();
?>
