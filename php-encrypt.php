<?php

function encrypt($password, $text) {
	
	// move text to base64
	$base64 = base64_encode( $text );
	
	// text string to array
	$arr = str_split($base64);

	// arr of password
	$arrPass = str_split($password);
	$lastPassLetter = 0;
	
	// encrypted string
	$encrypted = '';
	
	// encrypt
	for ($i=0; $i < sizeof( $arr ); $i++) {
		
		$letter = $arr[ $i ];
		
		$passwordLetter = $arrPass[ $lastPassLetter ];
		
		$temp = getLetterFromAlphabetForLetter( 
			$passwordLetter, $letter );
		
		if ($temp != null) {
			// concat to the final response encrypted string
			$encrypted .= $temp;
		} else {
			// if any error, return null
			return null;
		}		
		
		/*
			This is important: if we're out of letters in our 
			password, we need to start from the begining.
		*/
		if ($lastPassLetter == ( sizeof( $arrPass ) - 1) ) {
			$lastPassLetter = 0;
		} else {
			$lastPassLetter ++;
		}		
	}
	
	// We finally return the encrypted string
	return $encrypted;
}


function getLetterFromAlphabetForLetter( $letter, $letterToChange) {

	// this is the alphabet we know, plus numbers and the = sign 
	$abc = 'abcdefghijklmnopqrstuvwxyz0123456789=ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
	// get the position of the given letter, according to our abc
	$posLetter = strpos( $abc, $letter );
	
	// if we cannot get it, then we can't continue
	if ($posLetter === false) {
		echo 'Password letter ' . $letter . ' not allowed.';
		return null;
	}

	// according to our abc, get the position of the letter to encrypt
	$posLetterToChange = strpos( $abc, $letterToChange );
	
	// again, if any error, we cannot continue...
	if ($posLetterToChange == false) {
		echo 'Password letter ' . letter . ' not allowed.';
		return null;
	}
	
	// let's build the new abc. this is the important part
	$part1 = substr( $abc, $posLetter, strlen( $abc ) );
	$part2 = substr( $abc, 0, $posLetter);
	$newABC = '' . $part1 . '' . $part2;
	
	// we get the encrypted letter
	$temp = str_split($newABC);
	$letterAccordingToAbc = $temp[ $posLetterToChange ];
	
	// and return to the routine...
	return $letterAccordingToAbc;	
}

echo encrypt("pass", "Hello World");

?>
