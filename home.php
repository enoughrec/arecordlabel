<?php

$index = file_get_contents('index.html');
$path = realpath(dirname( __FILE__ ));

if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'facebook') !== false) {
	
	$segments = explode('/', $_SERVER['REQUEST_URI']);
    
	switch($segments[1]) {
		case 'release':
			$cat = $segments[2];
			
			$json = file_get_contents($path."/data/all.json");
			$releases = json_decode($json,true);
			// print_r($releases);

			// is probably a FB robot, looking for content
			// search for enrmp001
			$data = false;

			$segments = explode('/', $_SERVER['REQUEST_URI']);
			$cat = $segments[2];

			foreach ($releases as $k => $v) {
				if ($v['cat'] === $cat) {
					$data = $v;
					$data['info_en'] = strip_tags($data['info_en']);
					$data['url'] = 'http://enoughrecords.scene.org/release/'.$cat;
				}
			}

			if ($data) {
				include_once($path.'/robot_release.php');
			} else  {
				echo $index;
			}
		break;
		case 'blog':
		
			$blog = $segments[2];
			
			// is probably a FB robot, looking for content
			$data = false;
			
			//TODO: how do we find the correct .fm by knowing it's url?!
			//TODO: fill out $data['info_en'] << blog text
			//TODO: fill out $data['title']
			//TODO: fill out $data['date']
			//TODO: fill out $data['cover'] << first image found
			//TODO: fill out $data['url']

			if ($data) {
				include_once($path.'/robot_blog.php');
			} else  {
				echo $index;
			}
		
		break;
		default:
			echo $index;
		break;
	}
    
} else {
    echo $index;
}
