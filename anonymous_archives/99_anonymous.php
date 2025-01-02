<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>99 Anonymous</title>
	<meta name="description" content="Free music for free people undertaking a social revolution" />
	<link rel="image_src" type="image/jpeg" href="https://enoughrecords.scene.org/covers/enrmix12.jpg" />
	<link rel="shortcut icon" href="icon.png">
	<meta name="keywords" content="enough, enoughrecords, netlabel, music label, free music, netmusic, netaudio, demoscene, musica grátis, musica gratuita, free download, music, anonymous, wikileaks, occupy wall street, #antisec, lulzsec, 99 anonymous, 99%, we are anonymous, SOPA, PIPA, CISPA, #pl118, #pl228">
	<style type="text/css">
	
		@font-face {
		  font-family: myfont;
		  src: url('font.ttf');
		}
		
		body {
			background-color: transparent;
			margin: 0px;
			padding: 0px;
			font-family: myfont;
		    color: #fff;
		}
		
		#container
		{
			margin: 4em 6em 4em 6em;
			width: 750px; 
			position: relative;
			margin-left: auto;
			margin-right: auto;
		}
		
		h1 {
			color: white;
		}
		
		A 			{ color: #D6D6D6; /* text-decoration: none; */} 
		A:link		{ color: #D6D6D6; text-decoration: underline; } 
		A:visited	{ color: #D6D6D6; text-decoration: underline; } 
		A:active	{ color: #D6D6D6;  } 
		A:hover		{ color: #D6D6D6; text-decoration:underline; }
		
		#left-side
		{
			position: absolute;
			left: 0px;
			top: 0px;
			width: 350px;
			margin: 6px;
			border:2px solid;
			border-radius:25px;
			border-color: #999;
		}
		
		#right-side {
			position: absolute;
			left: 375px;
			top: 0px;
			width: 350px;
			margin: 6px;
			border:2px solid;
			border-radius:25px;
			border-color: #999;
		}
		
		#bottom {
			clear: both;
			position: relative;
			width: 750px;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
		}

		p {
		  font-weight: normal;
		  margin: 1em 1em 1em 1em;
		  text-align: justify
		 }
		
		hr
		 {
		  clear: both;
		  height: 1em;
		  visibility: hidden;
		 }
		
		em
		 {
		  background-color: #ffffff;
		  color: #8c9bb9;
		  font-style: normal;
		 }
		
		ul {
			margin: 1em;
			padding-left: 0px;
		}

		sub
		 {
		  display: none
		 }

		
	</style> 

	
	<script type="text/javascript">

	function getParameterByName(name)
	{
	  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	  var regexS = "[\\?&]" + name + "=([^&#]*)";
	  var regex = new RegExp(regexS);
	  var results = regex.exec(window.location.href);
	  if(results == null)
		return "";
	  else
		return decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	function load() {
		document.getElementById('lsel').selectedIndex = 0;
		loadLeft('99anonymous');
		
		switch (getParameterByName('m')) {
			case '1':		
				document.getElementById('rsel').selectedIndex = 4;
				loadRight('mixtape1');
			break;
			case '2':		
				document.getElementById('rsel').selectedIndex = 3;
				loadRight('mixtape2');
			break;
			case '3':		
				document.getElementById('rsel').selectedIndex = 2;
				loadRight('mixtape3');
			break;
			case '4':		
				document.getElementById('rsel').selectedIndex = 1;
				loadRight('mixtape4');
			break;
			default:
			case '5':		
				document.getElementById('rsel').selectedIndex = 0;
				loadRight('mixtape5');
			break;
		}
	}

	function loadLeft(value) {
		var text = '';
		
		switch(value) {
			case '99anonymous':
				text = '99 anonymous is a series of mixtapes organized by <a href="https://enoughrecords.scene.org" target=_blank>Enough Records Netlabel</a> and <a href="https://xdatelier.org" target=_blank>xDA hackerspace</a>.<br><br>\
				The project was started in December 2011 with the objective of raising awareness to ideologies behind the Anonymous, #AntiSec, Wikileaks and Occupy Wall Street movements. Three mixtapes were released in January and February 2012.<br><br>\
				In February 2012 we opened a call for artists to continue the mixtape series with a refocus on SOPA, PIPA, ACTA, the MegaUpload takedown and #pl118. Mixtape Volume 4 was released.<br><br>\
				In May 2012 we opened a call for artists to continue the mixtape series with a refocus on CISPA. Mixtape Volume 5 was released.<br><br>\
				Please listen, share and feel inspired to fix our world.';
				break;
			case 'anonymous':
				text = 'Anonymous broadly represents the concept of any and all people as an unnamed collective. As a multiple-use name, individuals who share in the "Anonymous" moniker also adopt a shared online identity, characterized as hedonistic and uninhibited.<br/><br/>The Anonymous collective has become increasingly associated with collaborative, international hacktivism, undertaking protests and other actions, often with the goal of promoting internet freedom and freedom of speech. Actions credited to "Anonymous" are undertaken by unidentified individuals who apply the Anonymous label to themselves as attribution.<br><br>Links: [<a href="https://www.wired.com/threatlevel/2011/11/anonymous-101/all/1" target=_blank>1</a>] [<a href="https://en.wikipedia.org/wiki/Anonymous_%28group%29" target=_blank>2</a>] [<a href="https://vimeo.com/19806469" target=_blank>3</a>] [<a href="https://www.aljazeera.com/indepth/opinion/2011/02/201121321487750509.html" target=_blank>4</a>] [<a href="https://anonops.blogspot.com/" target=_blank>5</a>] [<a href="https://youranonnews.tumblr.com/" target=_blank>6</a>]';
				break;
			case 'antisec':
				text = 'The LulzSec hacking group formed in May 2011 and came to international prominence after hacking the websites of the Public Broadcasting Service, Sony, and the United States Senate.<br><br>On 20 June 2011, LulzSec announced that they were teaming up with hacking collective Anonymous for a series of attacks they dubbed Operation Anti-Security or Operation AntiSec. The press release accompanying the beginning of the operation called for supporters to steal and publish classified government documents. Major banks and corporations were also mentioned as potential targets.<br><br>Links: [<a href="https://en.wikipedia.org/wiki/Operation_AntiSec" target=_blank>1</a>] [<a href="https://attrition.org/security/rant/sony_aka_sownage.html" target=_blank>2</a>] [<a href="https://twitter.com/LulzSec" target=_blank>3</a>] [<a href="https://www.abc.net.au/technology/articles/2011/06/20/3248520.htm" target=_blank>4</a>] [<a href="https://www.youtube.com/watch?v=p-p7nPPdTnk" target=_blank>5</a>] [<a href="https://www.ibtimes.com/articles/174362/20110705/anonymous-redhack-turkey-sivas-katliam-massacre-adnan-oktar-websites-hack-1000-protest.htm" target=_blank>6</a>] [<a href="https://bits.blogs.nytimes.com/2011/07/08/antisec-hackers-hit-f-b-i-contractor/" target=_blank>7</a>] [<a href="https://www.sfgate.com/cgi-bin/article.cgi?f=/g/a/2012/03/08/bloomberg_articlesM0L0NO6S972A01-M0LIE.DTL" target=_blank>8</a>] [<a href="https://motherjones.com/politics/2012/03/sabu-fbi-anonymous-hacker-informant" target=_blank>9</a>]'
				break;
			case 'wikileaks':
				text = 'WikiLeaks is a not-for-profit media organisation. It\'s goal is to bring important news and information to the public. Wikileaks provides an innovative, secure and anonymous way for sources to leak information to journalists. One of the most important activities is to publish original source material alongside news stories so readers and historians can see evidence of the truth.<br><br>WikiLeaks has sustained and triumphed against legal and political attacks designed to silence the organisation, their journalists and anonymous sources. The broader principles on which the work is based are the defence of freedom of speech and media publishing, the improvement of our common historical record and the support of the rights of all people to create new history.<br><br>Links: [<a href="https://wikileaks.org/" target=_blank>1</a>] [<a href="https://en.wikipedia.org/wiki/WikiLeaks" target=_blank>2</a>] [<a href="https://www.guardian.co.uk/world/2010/apr/05/wikileaks-us-army-iraq-attack" target=_blank>3</a>] [<a href="https://www.time.com/time/magazine/article/0,9171,2034488,00.html" target=_blank>4</a>]';
				break;
			case 'occupy':
				text = 'Occupy Wall Street is a people-powered movement that began on September 17, 2011 in Liberty Square in Manhattan’s Financial District, and has spread to over 100 cities in the United States and actions in over 1,500 cities globally.<br><br>#ows is fighting back against the corrosive power of major banks and multinational corporations over the democratic process, and the role of Wall Street in creating an economic collapse that has caused the greatest recession in generations.<br><br>The movement is inspired by popular uprisings in Egypt and Tunisia, and aims to fight back against the richest 1% of people that are writing the rules of an unfair global economy that is foreclosing on our future.<br><br>Links: [<a href="https://occupywallst.org/" target=_blank>1</a>] [<a href="https://en.wikipedia.org/wiki/Occupy_Wall_Street" target=_blank>2</a>] [<a href="https://edition.cnn.com/2011/10/05/opinion/rushkoff-occupy-wall-street/index.html" target=_blank>3</a>] [<a href="https://www.guardian.co.uk/world/occupy-wall-street" target=_blank>4</a>] [<a href="https://wearethe99percent.tumblr.com/" target=_blank>5</a>]';
				break;
			case 'pool1':
				text = 'Mixtapes 1, 2 and 3 were put together using material from Track Pool 1 and random speech samples taken from youtube. All tracks are licensed under by-nc-sa.<br><br>\
				The theme of Track Pool 1 was Anonymous / #antisec / Wikileaks / Occupy Wall Street.<br><br>\
				Download bundle (<a href="https://scene.org/file.php?file=%2Fmusic%2Fgroups%2Fenough_records%2F99_anonymous_pool_1.zip&fileinfo">.zip</a> - 444MB)<br><ul>\
		<li><a href="https://soundcloud.com/crkcrkcrk" target=_blank>.crk</a> - SQL Slammer (Anonymous Version)</li>\
		<li><a href="https://soundcloud.com/amitron_7" target=_blank>amitron_7</a> - Zuccotti Park</li>\
		<li>Copy Your Idols - <a href="https://www.youtube.com/watch?v=M3rJV1SlL5I" target=_blank>What If (Ron\'s Revolution Mix)</a></li>\
		<li>Ink & One - <a href="https://www.youtube.com/watch?v=TzNVodxr9R4" target=_blank>I Occupy wall street</a></li>\
		<li><a href="https://www.alteredstateofmine.net/" target=_blank>Jared Balogh</a> - The Calm Before The Storm</li>\
		<li>Jari Pitkänen - Vuvukat</li>\
		<li><a href="https://www.myspace.com/mpex" target=_blank>M-PeX</a> - Navimort</li>\
		<li>macabro - Just Run Away</li>\
		<li>macabro - We Live Lies</li>\
		<li><a href="https://www.mystifiedmusic.com/" target=_blank>Mystified</a> - The Shock Of Coming Days (Kinetic Remix)</li>\
		<li>No Loli-Gagging - Flurry</li>\
		<li>No Loli-Gagging - Occupy The Internet</li>\
		<li>No Loli-Gagging - Ochlocracy (original)</li>\
		<li>No Loli-Gagging - Ochlocracy (secondary)</li>\
		<li><a href="https://ocp.pt.vu/" target=_blank>ocp</a> - Persevere</li>\
		<li><a href="https://tpolm.org/~ps" target=_blank>ps</a> - Wake up! Your democracy is dead</li>\
		<li>Ricardo Webbens - port 21</li>\
		<li>Sci-Fi Industries - GOP Item</li>\
		<li>unknown artist - dreams</li>\
		<li>unknown artist - misunderstanding</li>\
		<li>Virgilio Oliveira feat. a poem by divinity33372 - Opression</li>\
		<li><a href="ytcracker.com" target=_blank>YTCracker</a> - #antisec</li>\
	</ul>\
				';
				break;		
			case 'sopa':
				text = 'The Stop Online Piracy Act (SOPA) is a United States bill introduced by U.S. Representative Lamar S. Smith (R-TX) to expand the ability of U.S. law enforcement to fight online trafficking in copyrighted intellectual property and counterfeit goods. Provisions include the requesting of court orders to bar advertising networks and payment facilities from conducting business with infringing websites, and search engines from linking to the sites, and court orders requiring Internet service providers to block access to the sites. The law would expand existing criminal laws to include unauthorized streaming of copyrighted content, imposing a maximum penalty of five years in prison. A similar bill in the U.S. Senate is titled the PROTECT IP Act (PIPA).<br><br>Links: [<a href="https://en.wikipedia.org/wiki/Stop_Online_Piracy_Act" target=_blank>1</a>] [<a href="https://www.youtube.com/watch?v=4GYzD-8BOQU&feature=related" target=_blank>2</a>] [<a href="https://www.forbes.com/sites/erikkain/2012/01/19/4-5-million-people-signed-googles-anti-sopa-petition/" target=_blank>3</a>] [<a href="https://sopastrike.com/" target=_blank>4</a>] [<a href="https://protestsopa.org/" target=_blank>5</a>] [<a href="https://torrentfreak.com/sopa-is-baaack-120117/">6</a>] [<a href="https://torrentfreak.com/how-we-stopped-sopa-120522/">7</a>]';
				break;
			case 'acta':
				text = 'The Anti-Counterfeiting Trade Agreement (ACTA) is a multi-national agreement for the purpose of establishing international standards for intellectual property rights enforcement. The agreement aims to establish an international legal framework for targeting counterfeit goods, generic medicines and copyright infringement on the Internet, and would create a new governing body outside existing forums, such as the World Trade Organization, the World Intellectual Property Organization, or the United Nations.<br><br>\
				The agreement was signed on 1 October 2011 by Australia, Canada, Japan, Morocco, New Zealand, Singapore, South Korea and the United States. In January 2012, the European Union and 22 of its member states signed as well, bringing the total number of signatories to 31. After ratification by 6 states, the convention will come into force.<br><br>\
				Supporters have described the agreement as a response to "the increase in global trade of counterfeit goods and pirated copyright protected works". Large intellectual property-based organizations such as the MPAA and Pharmaceutical Research and Manufacturers of America were active in the treaty\'s development.<br><br>Links: [<a href="https://en.wikipedia.org/wiki/Anti-Counterfeiting_Trade_Agreement" target=_blank>1</a>] [<a href="https://www.youtube.com/watch?v=xoW26CwhcR8" target=_blank>2</a>] [<a href="https://en.act-on-acta.eu/Main_Page" target=_blank>3</a>] [<a href="https://www.guardian.co.uk/commentisfree/2012/feb/03/act-acta-democracy-free-speech" target=_blank>4</a>] [<a href="https://torrentfreak.com/acta-is-dead-after-european-parliament-vote-120704/">5</a>]';
				break;
			case 'pl118':
				text = 'On 2 May 2011 the Ministry of Culture in Portugal made public a law proposal concerning the regulation of private copying levies (the English version of which is unfortunately not available). The law currently in force dates from 2004 and had in turn made some changes to the original law, dated from 1998.<br><br>\
				Portuguese copyright law would have an economic right, subject to mandatory collective management, which cannot be waived. Another innovation of this proposal is the inclusion in its scope of digital recording devices, which were traditionally exempted from levies altogether. The drafters decided to include in the list of devices subject to levies: USB sticks (0,06 euro per GB); external drives (0,02 euro per GB); hard drives (0,02 euro per GB, plus 0,005 per GB over 1 TB); devices used to reproduce audio, visual or audiovisual files in compressed form, integrated or not in other devices, like mobile phones (0,50 euro per GB).<br><br>Links: [<a href="https://kluwercopyrightblog.com/2011/05/12/a-wolf-in-sheeps-clothing-the-new-portuguese-proposal-on-private-copying-levies/" target=_blank>1</a>] [<a href="https://jonasnuts.com/423564.html" target=_blank>2</a>] [<a href="https://paulasimoesblog.wordpress.com/2012/01/03/as-taxas-pela-copia-privada/" target=_blank>3</a>] [<a href="https://jonasnuts.com/425057.html" target=_blank>4</a>]';
				break;
			case 'pool2':
				text = 'Mixtape 4 was put together using material from Track Pool 2 and random samples from youtube. All tracks are licensed under by-nc-sa.<br><br>\
				The theme of Track Pool 2 was SOPA / PIPA / ACTA / #pl118.<br><br>\
				Download bundle (<a href="https://scene.org/file.php?file=%2Fmusic%2Fgroups%2Fenough_records%2F99_anonymous_pool_2.zip&fileinfo">.zip</a> - 134MB)<br><ul>\
		<li>Copy Your Idols - Art is Dead (Ifland & Klatt\'s Final Nail Edit)</li>\
		<li>Dainon - ACTA</li>\
		<li>Frank Boyant - Starving to Death</li>\
		<li>Hanging by a Name - The Shape</li>\
		<li>Kokori - pl118</li>\
		<li>Pedro Esteves - #pl118</li>\
		<li>The Easton Ellises - Glitches</li>\
		<li>unknown artist - occupy</li>\
	</ul>\
				';
				break;
			case 'cispa':
				text = 'The Cyber Intelligence Sharing and Protection Act (CISPA) is a proposed law in the United States which would allow for the sharing of Internet traffic information between the U.S. government and certain technology and manufacturing companies. The stated aim of the bill is to help the U.S government investigate cyber threats and ensure the security of networks against cyberattack.\
CISPA has been criticized by advocates of Internet privacy and civil liberties, such as the Electronic Frontier Foundation, the American Civil Liberties Union, and Avaaz.org. Those groups argue CISPA contains too few limits on how and when the government may monitor a private individual’s Internet browsing information. Additionally, they fear that such new powers could be used to spy on the general public rather than to pursue malicious hackers. CISPA has garnered favor from corporations and lobbying groups such as Microsoft, Facebook and the United States Chamber of Commerce, which look on it as a simple and effective means of sharing important cyber threat information with the government.\
Some critics saw CISPA as a second attempt at strengthening digital piracy laws after the anti-piracy Stop Online Piracy Act became deeply unpopular. Intellectual property theft was initially listed in the bill as a possible cause for sharing Web traffic information with the government, though it was removed in subsequent drafts.<br><br>Links: [<a href="https://en.wikipedia.org/wiki/Cyber_Intelligence_Sharing_and_Protection_Act" target=_blank>1</a>] [<a href="https://www.tgdaily.com/security-features/62803-how-to-stop-cyber-spying-and-protest-cispa" target=_blank>2</a>] [<a href="https://www.huffingtonpost.com/2012/04/25/anti-cispa-petition-avaaz_n_1450809.html" target=_blank>3</a>]';
				break;
			case 'pool3':
				text = 'Mixtape 5 was put together using material from Track Pool 3 and random samples from youtube. All tracks are licensed under by-nc-sa.<br><br>\
				The theme of Track Pool 3 was CISPA.<br><br>\
				Download bundle (<a href="https://scene.org/file.php?file=%2Fmusic%2Fgroups%2Fenough_records%2F99_anonymous_pool_3.zip&fileinfo">.zip</a> - 221MB)<br><ul>\
		<li>Copy Your Idols - Addicted to This Anxiety (CISPA intro mix)</li>\
		<li>Dominator - A New Hope</li>\
		<li>Dominator - THX-2010</li>\
		<li>EYE8SOCCER - Livre (e) Directo</li>\
		<li>Kokori - Protection</li>\
		<li>Merankorii - Essa é que é Essa</li>\
		<li>Meta-Human feat. PResie - The Hunt</li>\
		<li>Mystified - Spinning Slowly (Surveillance Remix)</li>\
		<li>Substak - Endless Wind</li>\
		<li>Substak - Phantasm</li>\
		<li>unknown artist - black flag</li>\
		<li>unknown artist - death race 2000</li>\
		<li>unknown artist - invasion from inner earth</li>\
		<li>unknown artist - kill us</li>\
		<li>unknown artist - nightmare city</li>\
	</ul>\
				';
				break;	
		}
		
		document.getElementById('left-side-content').innerHTML = text;
	}

	var mixtapeid = 0;

	function loadRight(value) {
		var text = '';
		
		switch(value) {
			case 'mixtape1':
				text = '<div><iframe width="100%" height="320" src="https://www.mixcloud.com/widget/iframe/?feed=%2Fenoughrecords%2F99-anonymous-mixtape-1%2F" frameborder="0" ></iframe><div style="clear:both; height:3px;"></div></div><br>Download Mixtape 1 (<a href="https://www.archive.org/download/enrmix12_99_anonymous_mixtape_1/enrmix12_99_anonymous_mixtape_1_vbr_mp3.zip">.zip</a> - 76MB) (<a href="https://www.archive.org/download/enrmix12_99_anonymous_mixtape_1/00_99_anonymous_mixtape_1.cue">.cue</a>)';
				if (mixtapeid != 1) {
					document.getElementById('right-side-content').innerHTML = text;
					mixtapeid = 1;
				}
				document.getElementById('right-side-content2').innerHTML = '';
				break;
			case 'mixtape2':
				text = '<div><iframe width="100%" height="320" src="https://www.mixcloud.com/widget/iframe/?feed=%2Fenoughrecords%2F99-anonymous-mixtape-2%2F" frameborder="0" ></iframe><div style="clear:both; height:3px;"></div></div><br>Download Mixtape 2 (<a href="https://www.archive.org/download/enrmix13_99_anonymous_mixtape_2/enrmix13_99_anonymous_mixtape_2_vbr_mp3.zip">.zip</a> - 70MB) (<a href="https://www.archive.org/download/enrmix13_99_anonymous_mixtape_2/00_99_anonymous_mixtape_2.cue">.cue</a>)';
				if (mixtapeid != 2) {
					document.getElementById('right-side-content').innerHTML = text;
					mixtapeid = 2;
				}
				document.getElementById('right-side-content2').innerHTML = '';
				break;
			case 'mixtape3':
				text = '<div><iframe width="100%" height="320" src="https://www.mixcloud.com/widget/iframe/?feed=%2Fenoughrecords%2F99-anonymous-mixtape-3%2F" frameborder="0" ></iframe><div style="clear:both; height:3px;"></div></div><br>Download Mixtape 3 (<a href="https://www.archive.org/download/enrmix14_99_anonymous_mixtape_3/enrmix14_99_anonymous_mixtape_3_vbr_mp3.zip">.zip</a> - 106MB) (<a href="https://www.archive.org/download/enrmix14_99_anonymous_mixtape_3/00_99_anonymous_mixtape_3.cue">.cue</a>)';
				if (mixtapeid != 3) {
					document.getElementById('right-side-content').innerHTML = text;
					mixtapeid = 3;
				}
				document.getElementById('right-side-content2').innerHTML = '';
				break;
			case 'mixtape4':
				text = '<div><iframe width="100%" height="320" src="https://www.mixcloud.com/widget/iframe/?feed=%2Fenoughrecords%2F99-anonymous-mixtape-4%2F" frameborder="0" ></iframe><div style="clear:both; height:3px;"></div></div><br>Download Mixtape 4 (<a href="https://www.archive.org/download/enrmix15_99_anonymous_mixtape_4/enrmix15_99_anonymous_mixtape_4_vbr_mp3.zip">.zip</a> - 109MB) (<a href="https://www.archive.org/download/enrmix15_99_anonymous_mixtape_4/00_99_anonymous_mixtape_4.cue">.cue</a>)';
				if (mixtapeid != 4) {
					document.getElementById('right-side-content').innerHTML = text;
					mixtapeid = 4;
				}
				document.getElementById('right-side-content2').innerHTML = '';
				break;
			case 'mixtape5':
				text = '<div><iframe width="100%" height="320" src="https://www.mixcloud.com/widget/iframe/?feed=%2Fenoughrecords%2F99-anonymous-mixtape-5%2F" frameborder="0" ></iframe><div style="clear:both; height:3px;"></div></div><br>Download Mixtape 5 (<a href="https://www.archive.org/download/enrmix16_99_anonymous_mixtape_5/enrmix16_99_anonymous_mixtape_5_vbr_mp3.zip">.zip</a> - 142MB) (<a href="https://www.archive.org/download/enrmix16_99_anonymous_mixtape_5/00_99_anonymous_mixtape_5.cue">.cue</a>)';
				if (mixtapeid != 5) {
					document.getElementById('right-side-content').innerHTML = text;
					mixtapeid = 5;
				}
				document.getElementById('right-side-content2').innerHTML = '';
				break;
		}

	}


	</script>
	
</head>

<body onload="load()">
<div id="container">
	<div id="left-side">
		<p>
		<select id="lsel" onchange="loadLeft(this.options[this.selectedIndex].value)">
			<option value="99anonymous">About</option>
			<option value="">-----</option>			
			<option value="anonymous">Anonymous</option>
			<option value="antisec">#AntiSec</option>
			<option value="wikileaks">Wikileaks</option>
			<option value="occupy">Occupy Wall Street</option>
			<option value="sopa">SOPA / PIPA</option>
			<option value="acta">ACTA</option>
			<option value="pl118">#pl118</option>
			<option value="cispa">CISPA</option>
			<option value="">-----</option>
			<option value="pool1">Track Pool 1</option>
			<option value="pool2">Track Pool 2</option>
			<option value="pool3">Track Pool 3</option>
		</select>
		</p>
		<p id="left-side-content"></p>
	</div>

	<div id="right-side">
		<p>
		<select id="rsel" onchange="loadRight(this.options[this.selectedIndex].value)">
			<option value="mixtape5">Mixtape 5</option>
			<option value="mixtape4">Mixtape 4</option>
			<option value="mixtape3">Mixtape 3</option>
			<option value="mixtape2">Mixtape 2</option>
			<option value="mixtape1">Mixtape 1</option>
		</select>
		</p>
		<p id="right-side-content"></p>
		<p id="right-side-content2"></p>
	</div>
	
</div>

</body>
</html>
