<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html> 
<head> 
	<title>Anonymous Archives</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		@font-face {
		  font-family: myfont;
		  src: url('font.ttf');
		}
		body {
			background-color: #000;
			margin: 0px;
			padding: 0px;
			font-family: myfont;
		}
		#container {
			position: absolute;
			top: 0px;
			left: 0px;
			right: 0px;
			bottom: 0px;
			background: #000;
 			background: -webkit-gradient(linear, left top, left bottom, from(#444), to(black));
  			background: -moz-linear-gradient(top, #444, black);
  			padding-left: 20%;
  			padding-right: 20%;
  			text-align: center;
		}
		h1 {
			color: white;
		}
		p {
			color: white;
			font-size: 1.2em;
		}
		
		A 		{ color: #D6D6D6; /* text-decoration: none; */} 
		A:link		{ color: #D6D6D6; text-decoration: underline; } 
		A:visited	{ color: #D6D6D6; text-decoration: underline; } 
		A:active	{ color: #D6D6D6;  } 
		A:hover		{ color: #D6D6D6; text-decoration:underline; }
		
		#content {
			height: 100%;
		}
		
		iframe {
			width: 100%;
			height: 100%;
			border: 0;
		}
</style> 

<script type="text/javascript">

	function loadFrame(value) {
		switch(value) {
			case 'consumer_fuckers':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="consumer_fuckers.php">no iframe support</iframe>';
				break;
			case '99_anonymous_1':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="99_anonymous.php?m=1">no iframe support</iframe>';
				break;
			case '99_anonymous_2':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="99_anonymous.php?m=2">no iframe support</iframe>';
				break;
			case '99_anonymous_3':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="99_anonymous.php?m=3">no iframe support</iframe>';
				break;
			case '99_anonymous_4':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="99_anonymous.php?m=4">no iframe support</iframe>';
				break;
			case '99_anonymous_5':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="99_anonymous.php?m=5">no iframe support</iframe>';
				break;
			case 'an_uto':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="an_uto.php">no iframe support</iframe>';
				break;
			case '1984':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="1984.php">no iframe support</iframe>';
				break;
			default:
			case 'twilight':
				//text = '';
				document.getElementById('content').innerHTML = '<iframe src="twilight.php">no iframe support</iframe>';
				break;	
		}
	}

</script>

</head> 
<body onload="loadFrame()"> 
	<div id="container">
			<h1>Anonymous Archives</h1>
			<p>socio-political activism through music, a sub-label of <a href="http://enoughrecords.scene.org">EnoughRecords Netlabel</a></p>
			<select id="sel" onchange="loadFrame(this.options[this.selectedIndex].value)">
				<option value="twilight">Twilight Star Channeling</option>
				<option value="1984">1984 / Back To The Future</option>
				<option value="an_uto">an/uto</option>
				<option value="99_anonymous_5">99 Anonymous Mixtape 5</option>
				<option value="99_anonymous_4">99 Anonymous Mixtape 4</option>
				<option value="99_anonymous_3">99 Anonymous Mixtape 3</option>
				<option value="99_anonymous_2">99 Anonymous Mixtape 2</option>
				<option value="99_anonymous_1">99 Anonymous Mixtape 1</option>
				<option value="consumer_fuckers">United Consumer Fuckers / Prepare For Revolution</option>
			</select>
			<br /><br />
			<div id="content" style="text-align: center;"></div>
	</div>

</body> 
</html> 
