<?php


$index = file_get_contents('index.html');
$path = realpath(dirname( __FILE__ ));

/*

{
    "cat": "enrmp001",
    "artist": "ps",
    "album": "from my own little corner",
    "release_date": "2002-01-14",
    "info_en": "3 track dark ambient ep from our staff member <a href=\"http:\/\/tpolm.org\/~ps\/\">ps<\/a>. Photo artwork by Joana Queir\u00f3z.",
    "info_pt": "EP de dark ambient de 3 temas feito pelo nosso membro de staff <a href=\"http:\/\/tpolm.org\/~ps\/\">ps<\/a>. Fotografia de Joana Queir\u00f3z.",
    "discogs": "http:\/\/www.discogs.com\/ps-From-My-Own-Little-Corner\/release\/606784",
    "jamendo": null,
    "sonicsquirrel": null,
    "scene_org": "http:\/\/scene.org\/file.php?file=%2Fmusic%2Fgroups%2Fenough_records%2Fenrmp001_ps_-_from_my_own_little_corner.zip&fileinfo",
    "bandcamp": "http:\/\/enoughrec.bandcamp.com\/album\/from-my-own-little-corner",
    "soundcloud": null,
    "itunes": null,
    "spotify": null,
    "mixcloud": null,
    "fma": null,
    "rym": "http:\/\/rateyourmusic.com\/release\/ep\/ps_f1\/from_my_own_little_corner\/",
    "musicbrainz": null,
    "dogmazic": null,
    "lastfm": "http:\/\/www.last.fm\/music\/ps\/from+my+own+little+corner",
    "tags": ["laidback", "dark", "ambient", ".pt"],
    "archiveorg": "http:\/\/www.archive.org\/details\/enrmp001_ps_-_from_my_own_little_corner",
    "cover": "http:\/\/enoughrecords.scene.org\/covers\/enrmp001.jpg",
    "cc": "http:\/\/creativecommons.org\/licenses\/by-nc-sa\/3.0\/",
    "cc_img": "ccbyncsa.png",
    "tracks": ["http:\/\/http.de.scene.org\/pub\/music\/groups\/enough_records\/enrmp001_ps_-_from_my_own_little_corner\/01_ps_-_lost_in_familiar_alleys.mp3", "http:\/\/http.de.scene.org\/pub\/music\/groups\/enough_records\/enrmp001_ps_-_from_my_own_little_corner\/02_ps_-_while_she_is_not_here.mp3", "http:\/\/http.de.scene.org\/pub\/music\/groups\/enough_records\/enrmp001_ps_-_from_my_own_little_corner\/03_xhale_vs_ps_-_two_seconds_of_time.mp3"]
}

*/



if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'facebook') !== false) {
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
        include_once($path.'/robot.php');
    } else  {
        echo $index;
    }

    
} else {
    echo $index;
}
