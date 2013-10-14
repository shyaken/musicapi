<?php
	if(isset($data)){
		if(is_string($data)){
			echo $data;
		}
		else {
			echo json_encode($data);
		}
	}
	die();
?>