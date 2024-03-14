<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\Maths;

class Maths {
	protected bool $followOrder = true;

	protected function operation(Operation $operation, float ...$args): float|int {
		$followOrder = $this->followOrder;

		switch($operation) {
			case Operation::ADDITION:
				/**
				 * Sum = Addend + Addend
				 */
				return array_sum($args);
			case Operation::SUBTRACTION:
				/**
				 * Difference = Minuend - Subtrahend
				 */
				$diff = null;
				foreach($args as $arg) {
					if(is_numeric($diff)) {
						if(!$followOrder) {
							$diff = ($diff > $arg) ? ($diff - $arg) : ($arg - $diff);
						} else {
							$diff = ($diff - $arg);
						}
					} else {
						$diff = $arg;
					}
				}
				return $diff;
			case Operation::MULTIPLICATION:
				/**
				 * Product = Multiplier × Multiplicand
				 */
				$product = null;
				foreach($args as $arg) {
					if(is_numeric($product)) {
						if(!$followOrder) {
							$product = ($product > $arg) ? ($product * $arg) : ($arg * $product);
						} else {
							$product = ($product * $arg);
						}
					} else {
						$product = $arg;
					}
				}
				return $product;
			case Operation::DIVISION:
				/**
				 * Quotient = Dividend ÷ Divisor
				 */
				$quotient = null;
				foreach($args as $arg) {
					if(is_numeric($quotient)) {
						if(!$followOrder) {
							$quotient = ($quotient > $arg) ? ($quotient / $arg) : ($arg / $quotient);
						} else {
							$quotient = ($quotient / $arg);
						}
					} else {
						$quotient = $arg;
					}
				}
				return $quotient;
			case Operation::POWER:
				/**
				 * Power = Base⁽ᴱˣᴾᴼᴺᴱᴺᵀ⁾ (Base^Exponent)
				 */
				$power = null;
				foreach($args as $arg) {
					if(is_numeric($power)) {
						$power = pow($power, $arg);
					} else {
						$power = $arg;
					}
				}
				return $power;
			case Operation::ROOT:
				/**
				 * Root = Base^1/Exponent)
				 */
				$power = null;
				foreach($args as $arg) {
					if(is_numeric($power)) {
						$power = pow($power, 1/$arg);
					} else {
						$power = $arg;
					}
				}
				return $power;
			case Operation::LOGARITHM:
				/**
				 * Logarithm = logᵦₐₛₑ(anti-logarithm) (Base^Exponent)
				 */
				$log = null;
				$antiLog = null;

				foreach($args as $i => $arg) {
					if(($i % 2) == 0) {
						$antiLog = $arg;
					} else {
						$base = $arg;

						$log = log($antiLog, $base);
					}
				}
				return $log;
		}

		return -1;
	}

}
