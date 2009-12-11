<? 
	$root = "/wordalyzer";
	$defaultText = include 'default_text.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Wordalyzer</title>
		
		<link rel="stylesheet" href="<? echo $root ?>/css/wordalyzer_layout.css" type="text/css" media="screen" />
		
		<script type="text/javascript" src="<? echo $root ?>/js/analyze_ajax.js"></script>
	</head>

	<body><div id="container">
		
		<div id="header"> 
			<h1>Wordalyzer</h1>
		</div>
		
		<div id="content">
			<p>Enter the text to analyze below, then click submit!</p>
			<form method="post" action="">
				<p>
				<textarea id="words" name="words" rows="25" cols="100"><? echo $defaultText; ?></textarea>
				</p>
				<div id="error"> </div>
				<p>
				<input type="button" onclick="analyzeText()" value="Submit" />
				</p>
			</form>
			
			<!-- This area is populated by javascript -->	
			<div id="display">
			
				<h2>Favorite Words</h2>
				<div id="cloudHistogram">
				
				</div>
			
				<h2>Distribution</h2>
				<div id="distribution">
				
				
				</div>
					
			</div>
		</div>
		
		<div id="footer">
			<p>Copyright (C) 2009 Ralph Gootee. Made to impress wordnik in ~3hrs. Valid 
				<a href="http://validator.w3.org/check?uri=referer">xhtml</a> &amp;
			 	<a href="http://jigsaw.w3.org/css-validator/check/referer">css</a>
  			</p>
		</div>
			
	</div></body>
	
</html>