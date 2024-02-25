<?php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_detail_files_i1_v1.php';
$return = ritchey_detail_files_i1_v1("{$location}/temporary", 'sha256', TRUE);
if (is_array($return) === TRUE){
	$n = count($return);
	foreach ($return as &$item){
		$n--;
		$item = explode(',', $item);
		$item = implode(PHP_EOL, $item);
		if ($n < 1){
			echo $item . PHP_EOL;
		} else {
			echo $item . PHP_EOL . PHP_EOL;
		}
	}
	unset($item);
} else {
	echo "FALSE" . PHP_EOL;
}
?>