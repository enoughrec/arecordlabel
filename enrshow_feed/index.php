<?php
header('Content-type: application/xml');

require('../auth.php');
require('../misc2.php');

//var_dump($db);

$dbl = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
if(!$dbl) {
	die('["error":"SQL error... sorry ! ^^; I\'m on it !"]');
}
$query ='select enralbums.cat as cat, enralbums.artist as artist, enralbums.album as album, enralbums.release_date as release_date,';
$query.=' enralbums.shortnfo as info_en,';
$query.=' enralbums.sceneorg as scene_org';
$query.=' from enralbums where enralbums.cat like \'enrshow%\'';
$query.=' order by enralbums.release_date';
	
$list = array();

$result = mysqli_query($dbl, $query);
while($tmp = mysqli_fetch_array($result)) {
	//var_dump($tmp);
	//echo 'banana';
	//echo $tmp['artist'].'<br><br>';
	//echo $tmp['info_pt'].'<br><br>';
	//unset($tmp[0]);
	//unset($tmp[1]);
	//unset($tmp[2]);
	//unset($tmp[3]);
	//unset($tmp[4]);
	//unset($tmp[5]);
	//unset($tmp[6]);
	
//		$tmp['archiveorg'] = getArchiveOrg($tmp['cat'], $tmp['artist'], $tmp['album']);
	
	$tmp_array = array(); 
	$tmp_array['cat'] = $tmp['cat'];
	$tmp_array['artist'] = $tmp['artist'];
	$tmp_array['album'] = $tmp['album'];
	$tmp_array['release_date'] = $tmp['release_date'];
	$tmp_array['info_en'] = $tmp['info_en'];
	$tmp_array['cover'] = 'http://enoughrecords.scene.org/covers/'.$tmp['cat'].'.jpg';
	$tmp_array['tracks'] = json_decode( file_get_contents('../nfo/tracks_'.$tmp['cat'].'.json'), true);
	
	array_push($list, $tmp_array);
}

//var_dump($list);

/**
 * rss_feed (simple rss 2.0 feed creator php class)
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.1.0 (28 July 2013)
 *
 */
class rss_feed  {
 
  /**
   * Constructor
   *
   * @param array $a_db database settings
   * @param string $xmlns XML namespace
   * @param array $a_channel channel properties
   * @param string $site_url the URL of your site
   * @param string $site_name the name of your site
   * @param bool $full_feed flag for full feed (all topic content)
   */
  public function __construct($list, $xmlns, $a_channel, $site_url, $site_name, $full_feed = false) {
    // initialize
    //$this->db_settings = $a_db;
	$this->topics_list = $list;
    $this->xmlns = ($xmlns ? ' ' . $xmlns : '');
    $this->channel_properties = $a_channel;
    $this->site_url = $site_url;
    $this->site_name = $site_name;
    $this->full_feed = $full_feed;
  }
 
  /**
   * Generate RSS 2.0 feed
   *
   * @return string RSS 2.0 xml
   */
  public function create_feed() {
 
    $xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
 
    $xml .= '<rss version="2.0"' . $this->xmlns . '>' . "\n";
 
    // channel required properties
    $xml .= '<channel>' . "\n";
    $xml .= '<title>' . $this->channel_properties["title"] . '</title>' . "\n";
    $xml .= '<link>' . $this->channel_properties["link"] . '</link>' . "\n";
    $xml .= '<description>' . $this->channel_properties["description"] . '</description>' . "\n";
    $xml .= '<author>' . $this->channel_properties["author"] . '</author>' . "\n";
  
    // channel optional properties
    if(array_key_exists("language", $this->channel_properties)) {
      $xml .= '<language>' . $this->channel_properties["language"] . '</language>' . "\n";
    }
    if(array_key_exists("image_title", $this->channel_properties)) {
      $xml .= '<image>' . "\n";
      $xml .= '<title>' . $this->channel_properties["image_title"] . '</title>' . "\n";
      $xml .= '<link>' . $this->channel_properties["image_link"] . '</link>' . "\n";
      $xml .= '<url>' . $this->channel_properties["image_url"] . '</url>' . "\n";
      $xml .= '</image>' . "\n";
    }

	$xml .= '<itunes:author>' . $this->channel_properties["author"] . '</itunes:author>' . "\n";
	$xml .= '<itunes:summary>' . $this->channel_properties["description"] . '</itunes:summary>' . "\n";	
	$xml .= '<itunes:type>episodic</itunes:type>' . "\n";
	$xml .= '<itunes:owner>' . "\n";
	$xml .= '	<itunes:name>' . $this->channel_properties["author"] . '</itunes:name>' . "\n";
	$xml .= '	<itunes:email>' . $this->channel_properties["email"] . '</itunes:email>' . "\n";
	$xml .= '</itunes:owner>' . "\n";
	$xml .= '<itunes:explicit>No</itunes:explicit>' . "\n";
	$xml .= '<itunes:category text="Music">' . "\n";
	$xml .= '	<itunes:category text="Music"/>' . "\n";
	$xml .= '</itunes:category>' . "\n";
	$xml .= '<itunes:image href="' . $this->channel_properties["image_url"] . '"/>' . "\n";

    // get RSS channel items
    $now =  date("YmdHis"); // get current time  // configure appropriately to your environment
    $rss_items = $this->get_feed_items($now);
 
    foreach($rss_items as $rss_item) {
      $xml .= '<item>' . "\n";
      $xml .= '<title>' . $rss_item['title'] . '</title>' . "\n";
      $xml .= '<link>' . $rss_item['link'] . '</link>' . "\n";
      $xml .= '<description>' . $rss_item['description'] . '</description>' . "\n";
      $xml .= '<pubDate>' . $rss_item['pubDate'] . '</pubDate>' . "\n";
      $xml .= '<category>' . $rss_item['category'] . '</category>' . "\n";
      $xml .= '<source>' . $rss_item['source'] . '</source>' . "\n";
 
      $xml .= '<enclosure url="' . $rss_item['link'] . '" type="audio/mpeg"/>' . "\n";
      $xml .= '<itunes:summary>' . $rss_item['description'] . '</itunes:summary>' . "\n";
      $xml .= '<itunes:explicit>No</itunes:explicit>' . "\n";
	  $catnumber = intval(explode("w",$rss_item['cat'])[1]);
	  if ($catnumber < 26) {
		$xml .= '<itunes:duration>3600</itunes:duration>' . "\n";
	  } else {
		$xml .= '<itunes:duration>7200</itunes:duration>' . "\n";
	  }
      $xml .= '<itunes:image href="' . $rss_item['cover'] . '"/>' . "\n";
      $xml .= '<itunes:season>1</itunes:season>' . "\n";
      $xml .= '<itunes:episode>' . $catnumber . '</itunes:episode>' . "\n";
      $xml .= '<itunes:episodeType>full</itunes:episodeType>' . "\n";
	  
      if($this->full_feed) {
        $xml .= '<content:encoded>' . $rss_item['content'] . '</content:encoded>' . "\n";
      }
 
      $xml .= '</item>' . "\n";
    }
 
    $xml .= '</channel>';
 
    $xml .= '</rss>';
 
    return $xml;
  }
 
 
  /**
   * @param $rss_date
   * @param $rss_items_count
   * @internal param $rss_items
   * @return array
   */
  public function get_feed_items($rss_date, $rss_items_count = 10) {
 
    // connect to database
    //$conn = new mysqli($this->db_settings["db_server"], $this->db_settings["db_user"], $this->db_settings["db_passwd"], $this->db_settings["db_name"]);
 
    // check connection
    //if ($conn->connect_error) {
    //  trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
    //}
 
    // create array with topic IDs
    //$a_topic_ids = array();
    //$sql = 'SELECT id FROM topics ' .
    //  'WHERE date_published <= ' . "'" . $conn->real_escape_string($rss_date) . "'" .
    //  'AND date_published IS NOT NULL ' .
    //  'ORDER BY date_published DESC ' .
    //  'LIMIT 0,' . $rss_items_count;
 
    //$rs = $conn->query($sql);
    //if($rs === false) {
    //  $user_error = 'Wrong SQL: ' . $sql . '<br>' . 'Error: ' . $conn->errno . ' ' . $conn->error;
    //  trigger_error($user_error, E_USER_ERROR);
    //}
    //$rs->data_seek(0);
    //while($res = $rs->fetch_assoc()) {
    //  array_push($a_topic_ids, $res['id']);
    //}
    //$rs->free();
	
	$list = $this->topics_list;

    // get rss items according to http://www.rssboard.org/rss-specification
    $a_rss_items = array();
    $a_rss_item = array();
    $topic = array();
    foreach($list as $topic) {
 
      // get topic properties
      //$sql='SELECT * FROM topics WHERE id=' . $topic_id;
      //$rs=$conn->query($sql);
 
      //if($rs === false) {
      //  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
      //} else {
      //  $rs->data_seek(0);
      //  $topic = $rs->fetch_array(MYSQLI_ASSOC);
      //}
 
      // title
      $a_rss_item['title'] = $topic['album'];
 
      // link
      $a_rss_item['link'] = $topic['tracks'][0]; //$this->site_url . '/' . $topic['url'];
 
      // description
      $a_rss_item['description'] = $topic['info_en'];//'';
 
      //if($topic['image']) {
      //  $img_url = $this->site_url . $topic['image'];
      //  $a_rss_item['description'] = '<img src="' . $img_url . '" hspace="5" vspace="5" align="left"/>';
      //}
      //$a_rss_item['description'] .= $topic['description'];
 
      // pubdate -> configure appropriately to your environment
      $date = new DateTime($topic["release_date"]);
      $a_rss_item['pubDate'] = $date->format("D, d M Y H:i:s O");
 
      // category
      $a_rss_item['category'] = "Music"; //$topic["category"];
 
      // source
      $a_rss_item['source'] = "http://enoughrecords.scene.org/" . $topic['cat']; //$this->site_name;
 
      if($this->full_feed) {
        // content
        $a_rss_item['content'] = '<![CDATA[' . $topic['info_en'] .  ']]>';
      }
 
		// cover
      $a_rss_item['cover'] = $topic['cover'];
	
		// cat
      $a_rss_item['cat'] = $topic['cat'];
	  
      array_push($a_rss_items, $a_rss_item);
 
    }
 
    return $a_rss_items;
  }
 
}


// set more namespaces if you need them
$xmlns = 'xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"';
 
// configure appropriately - pontikis.net is used as an example
$a_channel = array(
  "title" => "Enough Records Radio Show",
  "link" => "http://enoughrecords.scene.org",
  "description" => "Music from Enough Records, the first Portuguese netlabel, releasing free music for free people since 2001.",
  "language" => "en",
  "image_title" => "Enough Records Radio Show",
  "image_link" => "http://enoughrecords.scene.org",
  "image_url" => "http://enoughrecords.scene.org/covers/300_ambient.png",
  "author" => "Enough Records",
  "email" => "ps@enoughrecords.org"
);
$site_url = 'http://enoughrecords.scene.org'; // configure appropriately
$site_name = 'Enough Records Radio Show'; // configure appropriately
 
$rss = new rss_feed($list, $xmlns, $a_channel, $site_url, $site_name);
echo $rss->create_feed();
?>