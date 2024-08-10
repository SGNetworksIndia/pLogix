<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusive with only "USAGE" access. "Modification", "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

use SGNetworks\pLogix\Maths\Arithmetics\Arithmetics;

require_once dirname(__DIR__, 2) . '/examples/header.php';

$class = Arithmetics::class;

$Arithmetics = new Arithmetics();

$v1 = 4;
$v2 = 2;
$v3 = 0;
$v4 = 1;

$sum = $Arithmetics->add($v1, $v2);
$t1 = $Arithmetics->getTime();
$tc1 = $Arithmetics->getTimeComplexity();
$tcc1 = $Arithmetics->getColorForComplexity();

$difference = $Arithmetics->subtract($v1, $v2);
$t2 = $Arithmetics->getTime();
$tc2 = $Arithmetics->getTimeComplexity();
$tcc2 = $Arithmetics->getColorForComplexity();

$product = $Arithmetics->multiply($v1, $v2);
$t3 = $Arithmetics->getTime();
$tc3 = $Arithmetics->getTimeComplexity();
$tcc3 = $Arithmetics->getColorForComplexity();

$quotient = $Arithmetics->divide($v1, $v2);
$t4 = $Arithmetics->getTime();
$tc4 = $Arithmetics->getTimeComplexity();
$tcc4 = $Arithmetics->getColorForComplexity();

$power = $Arithmetics->power($v1, $v2);
$t5 = $Arithmetics->getTime();
$tc5 = $Arithmetics->getTimeComplexity();
$tcc5 = $Arithmetics->getColorForComplexity();

$root = $Arithmetics->root($v1, $v2);
$t6 = $Arithmetics->getTime();
$tc6 = $Arithmetics->getTimeComplexity();
$tcc6 = $Arithmetics->getColorForComplexity();

$log = $Arithmetics->log($v1, $v2);
$t7 = $Arithmetics->getTime();
$tc7 = $Arithmetics->getTimeComplexity();
$tcc7 = $Arithmetics->getColorForComplexity();

$summation = $Arithmetics->summation($v1, $v2, $v3);
$t8 = $Arithmetics->getTime();
$tc8 = $Arithmetics->getTimeComplexity();
$tcc8 = $Arithmetics->getColorForComplexity();

$products = $Arithmetics->product($v1, $v2, $v4);
$t9 = $Arithmetics->getTime();
$tc9 = $Arithmetics->getTimeComplexity();
$tcc9 = $Arithmetics->getColorForComplexity();

function highlight(string $class, string $method, array $args = [], string $return = null): string {
	$class = "<span class=\"class\">$class</span>";
	$method = "<span class=\"method\">$method</span>";
	$return = (!empty($return)) ? ": <span class=\"return\">$return</span>" : '';

	$params = [];
	foreach($args as $param => $value) {
		$value = (is_array($value)) ? "<span class=\"value\">".implode("</span>, <span class=\"value\">",$value)."</span>" : "<span class=\"value\">$value</span>";
		$params[] = "<span class=\"parameter\">$param</span>$value";
	}
	$params = implode(', ', $params);

	return "<span class=\"code\">$class::$method($params)$return</span>";
}
$highlight = 'highlight';

echo <<<HTML
<table>
	<thead>
		<tr>
			<th>Calculation</th>
			<th>Function</th>
			<th>Input</th>
			<th>Output</th>
			<th>Time Consumed [in milliseconds (ms)]</th>
			<th>Time Complexity</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td>Addition</td>
			<td>{$highlight($class, 'add', ['...addends' => [$v1, $v2]], 'Sum')}</td>
			<td class="maths">$v1 + $v2</td>
			<td>$sum</td>
			<td style="color: $tcc1;">$t1</td>
			<td style="color: $tcc1;">$tc1</td>
		</tr>
		<tr>
			<td>Subtraction</td>
			<td>{$highlight($class, 'subtract', ['minuend' => $v1, '...subtrahends' => $v2], 'Difference')}</td>
			<td class="maths">$v1 - $v2</td>
			<td>$difference</td>
			<td style="color: $tcc2;">$t2</td>
			<td style="color: $tcc2;">$tc2</td>
		</tr>
		<tr>
			<td>Multiplication</td>
			<td>{$highlight($class, 'multiply', ['multiplier' => $v1, '...multiplicands' => $v2], 'Product')}</td>
			<td class="maths">$v1 × $v2</td>
			<td>$product</td>
			<td style="color: $tcc3;">$t3</td>
			<td style="color: $tcc3;">$tc3</td>
		</tr>
		<tr>
			<td>Division</td>
			<td>{$highlight($class, 'divide', ['dividend' => $v1, '...divisors' => $v2], 'Quotient')}</td>
			<td class="maths">$v1 ÷ $v2</td>
			<td>$quotient</td>
			<td style="color: $tcc4;">$t4</td>
			<td style="color: $tcc4;">$tc4</td>
		</tr>
		<tr>
			<td>Exponentiation</td>
			<td>{$highlight($class, 'power', ['base' => $v1, 'exponent' => $v2], 'Power')}</td>
			<td class="maths">$v1<sup>$v2</sup></td>
			<td>$power</td>
			<td style="color: $tcc5;">$t5</td>
			<td style="color: $tcc5;">$tc5</td>
		</tr>
		<tr>
			<td>Radical Expression</td>
			<td>{$highlight($class, 'root', ['radicand' => $v1, 'degree' => $v2], 'Root')}</td>
			<td class="maths">$$ {\sqrt[$v2]{$v1}} $$</td>
			<td>$root</td>
			<td style="color: $tcc6;">$t6</td>
			<td style="color: $tcc6;">$tc6</td>
		</tr>
		<tr>
			<td>Logarithm</td>
			<td>{$highlight($class, 'log', ['anti_log' => $v2, 'base' => $v1], 'Logarithm')}</td>
			<td class="maths">log<sub>$v2</sub>$v1</td>
			<td>$log</td>
			<td style="color: $tcc7;">$t7</td>
			<td style="color: $tcc7;">$tc7</td>
		</tr>
		<tr>
			<td>Summation</td>
			<td>{$highlight($class, 'summation', ['upper_bound' => $v1, 'term' => $v2, 'lower_bound' => $v3], 'Sum')}</td>
			<td class="maths">$$ {\sum_{i=$v3}^{$v1}{$v2}i} $$</td>
			<td>$summation</td>
			<td style="color: $tcc8;">$t8</td>
			<td style="color: $tcc8;">$tc8</td>
		</tr>
		<tr>
			<td>Product</td>
			<td>{$highlight($class, 'product', ['upper_bound' => $v1, 'term' => $v2, 'lower_bound' => $v4], 'Product')}</td>
			<td class="maths">$$ {\prod_{i=$v4}^{$v1}{$v2}i} $$</td>
			<td>$products</td>
			<td style="color: $tcc9;">$t9</td>
			<td style="color: $tcc9;">$tc9</td>
		</tr>
	</tbody>
</table>
HTML;

require_once dirname(__DIR__, 2) . '/examples/footer.php';