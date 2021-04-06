<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 26/3/19
 * Time: 2:58 PM
 */
$msg['error'] = true;
$msg1['error'] = false;
echo "<hr><pre>";
var_dump(isset($msg1['error']),isset($msg['error']));
die("<hr>test");
$str = "AS IS It’s All in a Name (A-E) FLASHSALE FINAL SALE";
echo "$str<hr><pre>";
$allWordsArr = str_word_count($str,1);
$capsCount = $smallCount = 0;
$res = array_map(function ($data) use (&$smallCount,&$capsCount) {
	if ($data == strtoupper($data))
		$capsCount++;
	elseif ($data == strtolower($data))
		$smallCount++;
	
},$allWordsArr);
if ($capsCount>2 || $smallCount>2)
{
	echo "<hr><pre>";
	var_dump(ucwords(strtolower($str)));
}
echo "<hr><pre>";
var_dump($smallCount,$capsCount);
echo "<hr><pre>";
var_dump($res,$allWordsArr);