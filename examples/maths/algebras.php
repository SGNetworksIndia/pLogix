<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusive with only "USAGE" access. "Modification", "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

use SGNetworks\pLogix\Maths\Algebra\Algebra;

$styles = 'th:nth-child(4),
td:nth-child(4) {
	min-width: 160px;
}';

require_once dirname(__DIR__, 2) . '/examples/header.php';

$class = Algebra::class;

$Algebra = new Algebra(5, true);

$v1 = 10;
$v2 = 8;
$v3 = 6;
$v4 = 4;
$v5 = 2;
$complex = true;
$separator = ',<br>';

$gcd = $Algebra->gcd($v1, $v2);
$t1 = $Algebra->getTime();
$tc1 = $Algebra->getTimeComplexity();
$tcc1 = $Algebra->getColorForComplexity();

$lcm = $Algebra->lcm($v1, $v2);
$t2 = $Algebra->getTime();
$tc2 = $Algebra->getTimeComplexity();
$tcc2 = $Algebra->getColorForComplexity();

$factor = implode(', ',$Algebra->factor($v1));
$t3 = $Algebra->getTime();
$tc3 = $Algebra->getTimeComplexity();
$tcc3 = $Algebra->getColorForComplexity();

$discriminant = $Algebra->discriminant($v1, $v2, $v3);
$t4 = $Algebra->getTime();
$tc4 = $Algebra->getTimeComplexity();
$tcc4 = $Algebra->getColorForComplexity();

$leq = $Algebra->linear($v1, $v2);
$t5 = $Algebra->getTime();
$tc5 = $Algebra->getTimeComplexity();
$tcc5 = $Algebra->getColorForComplexity();

$qeq = implode($separator,$Algebra->quadratic($v1, $v2, $v3, $complex));
$t6 = $Algebra->getTime();
$tc6 = $Algebra->getTimeComplexity();
$tcc6 = $Algebra->getColorForComplexity();

$cubic = implode($separator,$Algebra->cubic($v1, $v2, $v3, $v4, $complex));
$t7 = $Algebra->getTime();
$tc7 = $Algebra->getTimeComplexity();
$tcc7 = $Algebra->getColorForComplexity();

$quartic = implode($separator,$Algebra->quartic($v1, $v2, $v3, $v4, $v5, $complex));
$t8 = $Algebra->getTime();
$tc8 = $Algebra->getTimeComplexity();
$tcc8 = $Algebra->getColorForComplexity();

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
			<td>GCD</td>
			<td>{$highlight($class, 'gcd', ['x' => $v1, 'y' => $v2], 'GCD')}</td>
			<td class="maths">$$ {\gcd($v1,$v2)} $$</td>
			<td>$gcd</td>
			<td style="color: $tcc1;">$t1</td>
			<td style="color: $tcc1;">$tc1</td>
		</tr>
		<tr>
			<td>LCM</td>
			<td>{$highlight($class, 'lcm', ['x' => $v1, 'y' => $v2], 'LCM')}</td>
			<td class="maths">lcm($v1,$v2)</td>
			<td>$lcm</td>
			<td style="color: $tcc2;">$t2</td>
			<td style="color: $tcc2;">$tc2</td>
		</tr>
		<tr>
			<td>Factorization</td>
			<td>{$highlight($class, 'factor', ['x' => $v1], 'Factors')}</td>
			<td class="maths">$v1</td>
			<td>$factor</td>
			<td style="color: $tcc3;">$t3</td>
			<td style="color: $tcc3;">$tc3</td>
		</tr>
		<tr>
			<td>Discriminant</td>
			<td>{$highlight($class, 'discriminant', ['a' => $v1, 'b' => $v2, 'c' => $v3], 'Discriminant (Δ)')}</td>
			<td class="maths">$$ {\Delta = b^2 - 4ac \Rightarrow $v2^2 - 4\cdot$v1\cdot$v3} $$</td>
			<td>$discriminant</td>
			<td style="color: $tcc6;">$t6</td>
			<td style="color: $tcc6;">$tc6</td>
		</tr>
		<tr>
			<td>Linear Equation</td>
			<td>{$highlight($class, 'linear', ['a' => $v1, 'b' => $v2], 'Linear Root')}</td>
			<td class="maths">$$ {ax + b = 0 \Rightarrow {$v1}x + $v2 = 0} $$</td>
			<td>$leq</td>
			<td style="color: $tcc4;">$t4</td>
			<td style="color: $tcc4;">$tc4</td>
		</tr>
		<tr>
			<td>Quadratic Equation</td>
			<td>{$highlight($class, 'quadratic', ['a' => $v1, 'b' => $v2, 'c' => $v3], 'Quadratic Roots')}</td>
			<td class="maths">$$ {ax^2 + bx + c = 0 \Rightarrow {$v1}x^2 + {$v2}x + $v3 = 0} $$</td>
			<td>$qeq</td>
			<td style="color: $tcc5;">$t5</td>
			<td style="color: $tcc5;">$tc5</td>
		</tr>
		<tr>
			<td>Cubic Equation</td>
			<td>{$highlight($class, 'cubic', ['a' => $v1, 'b' => $v2, 'c' => $v3, 'd' => $v4], 'Cubic Roots')}</td>
			<td class="maths">$$ {ax^3 + bx^2 + cx + d = 0 \\ \Rightarrow {$v1}x^3 + {$v2}x^2 + {$v3}x + $v4 = 0} $$</td>
			<td>$cubic</td>
			<td style="color: $tcc7;">$t7</td>
			<td style="color: $tcc7;">$tc7</td>
		</tr>
		<tr>
			<td>Quartic Equation</td>
			<td>{$highlight($class, 'quartic', ['a' => $v1, 'b' => $v2, 'c' => $v3, 'd' => $v4, 'e' => $v5], 'Quartic Roots')}</td>
			<td class="maths">$$ {a_{4}x^4 + b_{3}x^3 + c_{2}x^2 + d_{1}x + e = 0 \\ \Rightarrow {$v1}x^4 + {$v2}x^3 + {$v3}x^2 + {$v4}x + $v5 = 0} $$</td>
			<td>$quartic</td>
			<td style="color: $tcc8;">$t8</td>
			<td style="color: $tcc8;">$tc8</td>
		</tr>
	</tbody>
</table>
HTML;

require_once dirname(__DIR__, 2) . '/examples/footer.php';