<?php 

// header('Content-type: application/json');

// error_reporting(E_ALL);
// ini_set('display_errors', '1');


// $cat = $_GET['cat'] ||  'enrmp326';
$cat = true;
//output
$audiolist = array();
$mp3s = new stdClass();

//echo 'hello world '.$_GET['cat'].'<br>';

//enrmp010 doesnt have .zip
//enrmp001 has .zip

if ($cat) {
	
	//echo 'loading json<br>';
	$data = file_get_contents('original.json');
	$data = json_decode($data);

	// print_r($data);
	
	foreach($data as $k => $entry){
	
		if (!isset($mp3s->{$entry->cat})) {
			$mp3s->{$entry->cat} = array();
		}

		if (true) {
			//echo $entry->download.'<br>';
			
			//$composite = explode("/",$entry->download);
			//echo $composite[sizeof($composite)-1].'<br>';
			
			$remotedir = $entry->download;
			//echo strlen($remotedir).'<br>';
			if (substr($remotedir,-4) == ".zip") {
				//echo 'its a zip<br>';
				$remotedir = substr($remotedir,0,strlen($remotedir)-4);
			} else {
				//echo 'its not a zip<br>';
			}
			//echo $remotedir.'<br>';
			$doc = new DOMDocument();
			@$doc->loadHTMLFile($remotedir);
			//var_dump($doc);
			@$links = $doc->getElementsByTagName('a');
			//var_dump($links);
			foreach ($links as $link) {
				$filetype = substr($link->nodeValue,-4);
				if (($filetype == ".mp3") || ($filetype == ".ogg")) {
					
					$thisfile = $remotedir."/".$link->nodeValue;
					//echo $thisfile."<br>";
					
					// array_push($audiolist, $thisfile);
					
					array_push($mp3s->{$entry->cat}, $thisfile);
				}
			}
		}   
		//$data[$k]->cover = 'http://tpolm.org/~ps/enough/covers/'.$entry->cat.'.jpg';
	}
}

//var_dump($audiolist);
$output = json_encode($mp3s);
echo $output;


