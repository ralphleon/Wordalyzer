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
		$words = preg_split('/\s+/',$text);
		
		$freq = array();
		$total = 0;
		$stops = 0;
		
		foreach($words as $item){
			
			// Remove the case
			$item = strtolower($item);
			$item = trim($item);
			$item = trim($item,'!@#$%^&*()_-+={}|[]\:";\'<>?,./');
			
			if($item != ""){ 		
				$total++;
				
				// Known word, new word, or stop word
				if(isset($freq[$item])){
					$freq[$item] += 1;
				}
				else if(!isset($stop[$item])){
					$freq[$item] = 1;
				}
				else {
					$stops++;
				}
			}
		}
		
		// We like our keys so we use arsort		
		arsort($freq);
		$n = count($freq);
		
		if($n < $limit) $limit = $n;
		
		$freq = array_slice($freq, 0, $limit);
	
		return array("freq" => $freq, "total" => $total, "stops" => $stops);
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
		
		$freq = $data["freq"];
		
		echo '<data>';
		// Replace me with XML
		foreach($freq as $key => $value){
			echo '<item title="' . $key .'" hits="'.$value.'"/>';		
		}
		echo '</data>';	
		
		echo '<stops>' . $data['stops'] . '</stops>';
		echo '<total>' . $data['total'] . '</total>';
	}else{
		$err ="Please ... enter some text in the text box ;)";
	}
	
	echo '<status>'; // error='. ($err != "") ? 'true' : 'false' . '>';
	echo $err;
	echo '</status>';
	
	echo '</packet>';
?>