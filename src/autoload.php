<?php
/*
 * Copyright (c) 2022 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification", "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

spl_autoload_register(function($className) {
	$className = str_replace("\\", "/", $className);
	$class = __DIR__ . "/$className";
	if(!class_exists($className)) {
		if(file_exists("$class.php")) {
			$file = $class . ".php";
		} elseif(file_exists("$class.class.php")) {
			$file = $class . ".class.php";
		} else {
			$file = $class . ".php";
		}
		if(file_exists($file)) {
			include_once($file);
		}
	}
});
