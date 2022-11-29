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

		$temp = getLetterFromAlphabetForLetter( $passwordLetter, $letter );

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

function decrypt($password, $text) {
	$lastPassLetter = 0;
	// this is the final decrypted string
	$decrypted = '';
	// let's start...
	for ($i = 0; $i < strlen($text); $i++) {
		// next letter from the string to decrypt
		$letter = $text[$i];
		// get the next letter from the password
		$passwordLetter = $password[$lastPassLetter];
		// get the decrypted letter according to the password
		$temp = getInvertedLetterFromAlphabetForLetter($passwordLetter, $letter);
		// concat the response
		$decrypted .= $temp;
		// if our password is too short, 
		// let's start again from the first letter  
		if ($lastPassLetter == strlen($password) - 1) {
			$lastPassLetter = 0;
		} else {
			$lastPassLetter++;
		}
	} 
	// return the decrypted string and converted 
	// from base64 to plain text 
	return base64_decode($decrypted);
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
		echo 'Password letter ' . $letterToChange . ' not allowed.';
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

function getInvertedLetterFromAlphabetForLetter($letter, $letterToChange) {
	// this is the alphabet we know, plus numbers and the = sign 
	$abc = 'abcdefghijklmnopqrstuvwxyz0123456789=ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$posLetter = strpos($abc, $letter);
	if ($posLetter === false) {
		echo 'Password letter ' . $letter . ' not allowed.';
		return null;
	}
	$part1 = substr($abc, $posLetter);
	$part2 = substr($abc, 0, $posLetter);
	$newABC = $part1 . $part2;	
	$posLetterToChange = strpos($newABC, $letterToChange);
	if ($posLetterToChange === false) {
		echo 'Password letter ' . $letterToChange . ' not allowed.';
		return null;
	}
	$letterAccordingToAbc = $abc[$posLetterToChange];
	return "$letterAccordingToAbc";
}

$encrypted = encrypt("password", "Hello World");
echo $encrypted."\n";
$plain =  decrypt("password", $encrypted);
echo $plain."\n";

