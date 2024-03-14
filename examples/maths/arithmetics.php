<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusive with only "USAGE" access. "Modification", "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

use SGNetworks\Maths\Arithmetics;

require_once dirname(__DIR__, 2) . '/examples/header.php';

$Arithmetics = new Arithmetics();

$v1 = 4;
$v2 = 2;

$sum = $Arithmetics->add($v1,$v2);
$difference = $Arithmetics->subtract($v1,$v2);
$product = $Arithmetics->multiply($v1,$v2);
$quotient = $Arithmetics->divide($v1,$v2);
$power = $Arithmetics->power($v2,$v1);
$root = $Arithmetics->root($v1,$v2);
$log = $Arithmetics->log($v1,$v2);

echo <<<HTML
<table>
	<thead>
		<tr>
			<th>Operation</th>
			<th>Input</th>
			<th>Output</th>
		</tr>
		<tr>
			<td>Addition</td>
			<td class="maths">$v1 + $v2</td>
			<td>$sum</td>
		</tr>
		<tr>
			<td>Subtraction</td>
			<td class="maths">$v1 - $v2</td>
			<td>$difference</td>
		</tr>
		<tr>
			<td>Multiplication</td>
			<td class="maths">$v1 × $v2</td>
			<td>$product</td>
		</tr>
		<tr>
			<td>Division</td>
			<td class="maths">$v1 ÷ $v2</td>
			<td>$quotient</td>
		</tr>
		<tr>
			<td>Exponentiation</td>
			<td class="maths">$v1<sup>$v2</sup></td>
			<td>$power</td>
		</tr>
		<tr>
			<td>Root</td>
			<td class="maths">
				$$ {\sqrt[$v2]{$v1}} $$
			</td>
			<td>$root</td>
		</tr>
		<tr>
			<td>Logarithm</td>
			<td class="maths">log<sub>$v2</sub>$v1</td>
			<td>$log</td>
		</tr>
	</thead>
</table>
HTML;

require_once dirname(__DIR__, 2) . '/examples/footer.php';