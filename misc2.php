<?

require('misc.php');

function getArchiveOrg($cat, $artist_name, $album_name) {

	$title = $cat;
	if (strncmp($cat,'enrmp',5) == 0) $title.="_".str_replace(' ', '_', strtolower($artist_name))."_-_";
	if (strncmp($cat,'enrmix',6) == 0) $title.="_";
	if (strncmp($cat,'enrshow',7) == 0) $title.="_";
	if (strncmp($cat,'enrcmp',6) == 0) $title.="_-_";
	if (strncmp($cat,'enrtxt',6) == 0) $title.="_".str_replace(' ', '_', strtolower($artist_name))."_-_";

	$title.=str_replace(' ', '_', strtolower($album_name));
	$title = replacetitlechars(stripslashes($title));
	
	if ($cat == "enrmp041") $title = "enrmp041_aquarelle_-_era_uma_vez_uma_cortina";
	if ($cat == "enrmp091") $title = "enrmp091_e4c_-_data_platform";
	if ($cat == "enrmp112") $title = "enrmp112_gilo_-_4a_at_electric";
	if ($cat == "enrmp118") $title = "enrmp118_soundzcapa_-_apage_satanas";
	if ($cat == "enrmp136") $title = "enrmp136_structura_-_inominavel_live01";
	if ($cat == "enrmp130") $title = "enrmp130_eclectric_-_operators_concepts";
	if ($cat == "enrmp159") $title = "enrmp159_velvet_narcosis_-_neutral";
	if ($cat == "enrmp184") $title = "enrmp184_umm_-_demo_2008";
	if ($cat == "enrmp187") $title = "enrmp187_seetyca_-_antlitz";
	if ($cat == "enrmp203") $title = "enrmp203_proyecto_de_prueba___void_null_-_proyecto_de_prueba___void_null_";
	if ($cat == "enrmp247") $title = "enrmp247_structura_-_karawane_live_thislac2009";
	if ($cat == "enrmix08") $title = "enrmix08_zero_is_enough___special_radialx_100704";
	if ($cat == "enrmp278") $title = "enrmp278_-_united_consumer_fuckers___prepare_for_revolution";
	if ($cat == "enrmp282") $title = "enrmp282_jared_balogh_-_chaotic_life_fin___revitalized_eyes";	
	if ($cat == "enrmp308") $title = "enrmp308_-_an_uto";
	if ($cat == "enrmp322") $title = "enrmp322_-_1984___back_to_the_future";
	if ($cat == "enrmp371") $title = "enrmp371_umm_-_estupidez_e_p_";
	if ($cat == "enrmp453") $title = "enrmp453_doissemicircuitosinvertidos_-_live_at_l_a_m_a__sessions";
	if ($cat == "enrmp455") $title = "enrmp455_ps_-_go______yourself";

	$archiveorg = "http://www.archive.org/details/".$title;
	
	if ($cat == "enrcmp09") $archiveorg = "http://www.archive.org/details/enrcmp09";
	if ($cat == "enrmp009") $archiveorg = "http://www.archive.org/details/enrmp009_ps_-_benfield_no_travel_sprite_bubbles_and_a_rice_cup_o";
	if ($cat == "enrmp013") $archiveorg = "http://www.archive.org/details/enrmp013_ps_-_dont";
	if ($cat == "enrmp043") $archiveorg = "http://www.archive.org/details/enrmp043_fp_-_traces";
	if ($cat == "enrmp080") $archiveorg = "http://www.archive.org/details/enrmp080__creeper_robusto__-_cloned_superstar";
	if ($cat == "enrmp097") $archiveorg = "http://www.archive.org/details/enrmp097__interrupt_jumper__-_urban_ghost_dance";
	if ($cat == "enrmp129") $archiveorg = "http://www.archive.org/details/enrmp129";
	if ($cat == "enrmp137") $archiveorg = "http://www.archive.org/details/enrmp137";
	if ($cat == "enrmp162") $archiveorg = "http://www.archive.org/details/enrmp162_a3eK_-_childhood";
	if ($cat == "enrmp175") $archiveorg = "http://www.archive.org/details/enrmp175_cachexy_-_u_to_L";
	if ($cat == "enrmp205") $archiveorg = "http://www.archive.org/details/enrmp205";
	if ($cat == "enrmp216") $archiveorg = "http://www.archive.org/details/enrmp216_victor_ivaniv_and_muhmood_-_rut";
	if ($cat == "enrmp242") $archiveorg = "http://www.archive.org/details/enrmp242_duas_semi_colcheias_invertidas_-_ii";
	if ($cat == "enrmp257") $archiveorg = "http://www.archive.org/details/enrmp257_duas_semi_colcheias_invertidas_-_i";
	if ($cat == "enrmp269") $archiveorg = "http://www.archive.org/details/enrmp269_varia_-_magic___omega";
	if ($cat == "enrmp271") $archiveorg = "http://www.archive.org/details/enrmp271_duas_semi_colcheias_invertidas_-_saditrevnisaiehclocimessaud";
	if ($cat == "enrmp314") $archiveorg = "http://www.archive.org/details/enrmp314_duas_semi_colcheias_invertidas_-_4";
	if ($cat == "enrmp430") $archiveorg = "http://www.archive.org/details/enrmp430_duas_semi_colcheias_invertidas___parpar_-_dsci___parpar_split";
	if ($cat == "enrmix20") $archiveorg = "http://www.archive.org/details/Freihoch2-2013-10-15-enoughRecords";

	return $archiveorg;
}


?>
