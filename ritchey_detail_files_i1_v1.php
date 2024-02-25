<?php
#Name:Ritchey Detail Files i1 v1
#Description:Provides information about all files in a directory (recursively). Returns an array of information. Returns "FALSE" on failure.
#Notes:Optional arguments can be "NULL" to skip them in which case they will use default values.
#Arguments:'source' (required) is the folder containing the files to examine. 'hashing_algorithm' (optional) is the hashing algorithm to use for hashing files. Valid values are 'sha256'. Default value is 'sha256'. 'display_errors' (optional) indicates if errors should be displayed.
#Arguments (Script Friendly):source:path:required,hashing_algorithm:string:optional,display_errors:bool:optional
#Content:
#<value>
if (function_exists('ritchey_detail_files_i1_v1') === FALSE){
function ritchey_detail_files_i1_v1($source, $hashing_algorithm = NULL, $display_errors = NULL){
	$errors = array();
	$location = realpath(dirname(__FILE__));
	if (@is_dir($source) === FALSE){
		$errors[] = 'source';
	}
	if ($hashing_algorithm === NULL){
		$hashing_algorithm = 'sha256';
	} else if ($hashing_algorithm === 'sha256'){
		//Do nothing
	} else {
		$errors[] = "hashing_algorithm";
	}
	if ($display_errors === NULL){
		$display_errors = FALSE;
	} else if ($display_errors === TRUE){
		#Do Nothing
	} else if ($display_errors === FALSE){
		#Do Nothing
	} else {
		$errors[] = "display_errors";
	}
	##Task
	if (@empty($errors) === TRUE){
		###Get a list of all files in source
		$location = realpath(dirname(__FILE__));
		require_once $location . '/dependencies/ritchey_list_files_i1_v1/ritchey_list_files_i1_v1.php';
		$files = ritchey_list_files_i1_v1($source, FALSE);
		###Hash each file
		$result = array();
		$checksums = array();
		foreach ($files as &$item1){
				$checksum = @hash_file('sha256', $item1);
				$id = @hash('sha256', "{$item1}{$checksum}");
				$type = mime_content_type($item1);
				//Check if duplicate of anything already found by comparing checksum
				$duplicate = 'No';
				if (@array_key_exists($checksum, $checksums) === TRUE){
					$duplicate = 'Yes';
				}
				if ($duplicate === 'Yes'){
					$result[] = "ID:{$id},File:{$item1},Checksum:SHA-256:{$checksum},Type:{$type},Duplicate:{$duplicate},Duplicates:{$checksums[$checksum]}";
				} else {
					$result[] = "ID:{$id},File:{$item1},Checksum:SHA-256:{$checksum},Type:{$type},Duplicate:{$duplicate}";
				}
				if ($duplicate === 'No'){
					$checksums[$checksum] = $item1;
				} else {
					$checksums[$checksum] = "{$checksums[$checksum]}|{$item1}";
				}
		}
		unset($item1);
	}
	result:
	##Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_detail_files_i1_v1_format_error') === FALSE){
				function ritchey_detail_files_i1_v1_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_detail_files_i1_v1_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	##Return
	if (@empty($errors) === TRUE){
		return $result;
	} else {
		return FALSE;
	}
}
}
#</value>
?>