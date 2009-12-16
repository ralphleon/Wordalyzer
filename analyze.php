<?
	/** analyzies a set of text and returns the top n touples of words with their frequency 
	 *
	 * @param $text the text to analyze
	 * @param $limit the maximum size of the returned array (to prevent overflows
	 *
	 * @returns an array of words paired with their frequencys in DESC order of length $limit
	 */ 
	function analyze($text,$limit)
	{
		$stop = include_once("stop_words.php");
		$words = split(" ",$text);
		
		$freq = array();
		
		foreach($words as $item){
			
			// Remove the case
			$item = strtolower($item);
			$item = trim($item);
			$item = trim($item,'!@#$%^&*()_-+={}|[]\:";\'<>?,./');
					
			if(isset($freq[$item])){
				$freq[$item] += 1;
			}
			else if(!isset($stop[$item]) && $item != ""){
				$freq[$item] = 1;
			}
		}
		
		// We like our keys so we use asort		
		arsort($freq);
		$n = count($freq);
		
		if($n < $limit) $limit = $n;
		
		$freq = array_slice($freq, 0, $limit);//,true);
	
		return $freq;
	}
?>

<?
	/** Main AJAX callback, constructs an XML message with the frequency data
	 *  TODO replace XML with JSON	
	 */
	 
	$text = $_POST['text'];
	$ignore = ($_POST['ignore'] == 'true') ? True : False;
	$max  = 50;
	$err = "";
	
	// Construct the XML response
	echo '<packet>';	
	
	// Construct the data portion
	if($text != ""){
		
		$data = analyze($text,$max);
		
		echo '<data>';
		// Replace me with XML
		foreach($data as $key => $value){
			echo '<item title="' . $key .'" hits="'.$value.'"/>';		
		}
		echo '</data>';	
	}else{
		$err ="Please ... enter some text in the text box ;)";
	}
	
	echo '<status>'; // error='. ($err != "") ? 'true' : 'false' . '>';
	echo $err;
	echo '</status>';
	
	echo '</packet>';
?>