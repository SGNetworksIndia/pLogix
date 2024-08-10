<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\pLogix\Maths\Arithmetics;

use SGNetworks\pLogix\Maths\Maths;
use SGNetworks\pLogix\Maths\Exception\OutOfBoundsException;
use function sqrt;
use function floor;
use function intdiv;

class Arithmetics extends Maths {
	/**
	 * Perform basic arithmetic <b>addition</b> operation on the <b>addends</b>
	 *
	 * @param float ...$addends The <b>addends</b> to add
	 *
	 * @return float|int The sum of the <b>addends</b>.
	 */
	public function add(float ...$addends): float|int {
		return $this->operation(ArithmeticOperation::ADDITION, ...$addends);
	}

	/**
	 * Perform basic arithmetic <b>subtraction</b> operation on the <b>minuend</b> by reducing it by the <b>subtrahends</b>
	 *
	 * @param float $minuend
	 * @param float ...$subtrahends
	 *
	 * @return float|int The sum of the <b>addends</b>.
	 */
	public function subtract(float $minuend, float ...$subtrahends): float|int {
		return $this->operation(ArithmeticOperation::SUBTRACTION, $minuend, ...$subtrahends);
	}

	public function multiply(float $multiplier, float ...$multiplicands): float|int {
		return $this->operation(ArithmeticOperation::MULTIPLICATION, $multiplier, ...$multiplicands);
	}

	public function divide(float $dividend, float ...$divisors): float|int {
		return $this->operation(ArithmeticOperation::DIVISION, $dividend, ...$divisors);
	}

	public function power(float $base, float $exponent): float|int {
		return $this->operation(ArithmeticOperation::POWER, $base, $exponent);
	}

	public function root(float $radicand, float $degree = 2): float|int {
		return $this->operation(ArithmeticOperation::ROOT, $radicand, $degree);
	}

	public function log(float $anti_log, float $base): float|int {
		return $this->operation(ArithmeticOperation::LOGARITHM, $anti_log, $base);
	}

	public function summation(float $upper_bound, float $term, float $lower_bound = 0): float|int {
		return $this->operation(ArithmeticOperation::SUMMATION, $upper_bound, $term, $lower_bound);
	}

	public function product(float $upper_bound, float $term, float $lower_bound = 1): float|int {
		return $this->operation(ArithmeticOperation::PRODUCT, $upper_bound, $term, $lower_bound);
	}

	/**
	 * Modulo (Binary operation)
	 *
	 * Modulo is different from the remainder function.
	 * The PHP % operator is the remainder function, where the result has the same sign as the dividend.
	 * The mod function's result has the same sign as the divisor.
	 *
	 * For positive dividends and divisors, the modulo function is the same as the remainder (%) operator.
	 * For negative dividends or divisors, the modulo function has different behavior than the remainder (%) operator.
	 *
	 * A mod n
	 *   a - n ⌊a/n⌋ for n ≠ 0
	 *   a for n = 0
	 * where
	 *   a is the dividend (integer)
	 *   n is the divisor, also known as the modulus (integer)
	 *   ⌊⌋ is the floor function
	 *
	 * @param int $a dividend
	 * @param int $n divisor, also known as the modulus
	 *
	 * @return int
	 *
	 * @see https://en.wikipedia.org/wiki/Modulo_operation
	 * @see https://en.wikipedia.org/wiki/Modulo_(mathematics)
	 */
	public function modulo(int $a, int $n): int {
		return $this->operation(ArithmeticOperation::MODULO, $a, $n);
	}

	/**
	 * Prime factorization
	 * The prime factors of an integer.
	 * https://en.wikipedia.org/wiki/Prime_factor
	 *
	 * Algorithm
	 *  1) Let n be the ongoing remainder
	 *  2) Try prime factoring n with 2 and 3
	 *  3) Try prime factoring n with increasing ℕ of the form 6𝑘±1 up through √n (all other ℕ are divisible by 2 and/or 3)
	 *  4) If n is still > 1, the remainder is a prime factor
	 *
	 * @param int $n
	 *
	 * @return int[] of prime factors
	 *
	 * @throws OutOfBoundsException if n is < 1.
	 */
	public function primeFactorization(int $n): array {
		return $this->operation(ArithmeticOperation::PRIME_FACTOR, $n);
	}

	private function operation(ArithmeticOperation $operation, float ...$args): float|int|array {
		$this->mark(count($args));
		$followOrder = $this->followOrder;

		switch($operation) {
			case ArithmeticOperation::ADDITION:
				/**
				 * Sum = Addend + Addend
				 */
				$sum = array_sum($args);
				$this->mark();
				return $sum;
			case ArithmeticOperation::SUBTRACTION:
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
				$this->mark();
				return $diff;
			case ArithmeticOperation::MULTIPLICATION:
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
				$this->mark();
				return $product;
			case ArithmeticOperation::DIVISION:
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
				$this->mark();
				return $quotient;
			case ArithmeticOperation::POWER:
				/**
				 * Power = Base⁽ᴱˣᴾᴼᴺᴱᴺᵀ⁾ (Base^Exponent)
				 */
				$base = $args[0];
				$exponent = $args[1];

				$power = pow($base, $exponent);

				$this->mark();
				return $power;
			case ArithmeticOperation::ROOT:
				/**
				 * Root = Base¹/ᴱˣᴾᴼᴺᴱᴺᵀ (Base^1/Exponent)
				 */
				$radicand = $args[0];
				$degree = $args[1];

				$root = pow($radicand, 1 / $degree);

				$this->mark();
				return $root;
			case ArithmeticOperation::LOGARITHM:
				/**
				 * Logarithm = logᵦₐₛₑ(anti-logarithm) (Base^Exponent)
				 */
				$antiLog = $args[0];
				$base = $args[1];

				$log = log($antiLog, $base);

				$this->mark();
				return $log;
			case ArithmeticOperation::SUMMATION:
				$ub = $args[0];
				$term = $args[1];
				$lb = $args[2];
				$sum = 0;

				for($i = $lb; $i <= $ub; $i++) {
					$sum += $term * $i;
				}

				$this->mark();
				return $sum;
			case ArithmeticOperation::PRODUCT:
				$ub = $args[0];
				$term = $args[1];
				$lb = $args[2];
				$product = 1;

				for($i = $lb; $i <= $ub; $i++) {
					$product *= $term * $i;
				}

				$this->mark();
				return $product;
			case ArithmeticOperation::MODULO:
				$a = $args[0];
				$n = $args[1];

				$mod = $n === 0.0 ? $a : (int)($a - $n * floor($a / $n));

				$this->mark();
				return $mod;
			case ArithmeticOperation::PRIME_FACTOR:
				$n = $args[0];
				if($n < 1) {
					//throw new OutOfBoundsException("n must be ≥ 1. ($n provided)");
				}

				$remainder = $n;
				$factors = [];

				foreach([2, 3] as $divisor) {
					while($remainder % $divisor === 0) {
						$factors[] = $divisor;
						$remainder = intdiv($remainder, $divisor);
					}
				}

				$divisor = 5;
				$rn = sqrt($remainder);

				while($divisor <= $rn) {
					while($remainder % $divisor === 0) {
						$factors[] = $divisor;
						$remainder = intdiv($remainder, $divisor);
						$rn = sqrt($remainder);
					}
					$divisor += 2;
					while($remainder % $divisor === 0) {
						$factors[] = $divisor;
						$remainder = intdiv($remainder, $divisor);
						$rn = sqrt($remainder);
					}
					$divisor += 4;
				}

				if($remainder > 1) {
					$factors[] = $remainder;
				}

				$this->mark();
				return $factors;
		}

		$this->mark();

		return -1;
	}

}
