<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

require_once dirname(__DIR__) . '/src/autoload.php';
$assets = "{$_SERVER['HTTP_HOST']}/examples/{$_SERVER['REQUEST_URI']}" . '/'
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?=$title ?? 'pLogix';?></title>
		<link rel="stylesheet" href="../assets/css/base.css">
		<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
		<script id="MathJax-script" src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js" async></script>
	</head>
	<body>
