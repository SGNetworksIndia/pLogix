<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\Maths;

class Arithmetics extends Maths {
	/**
	 * Perform basic arithmetic <b>addition</b> operation on the <b>addends</b>
	 *
	 * @param float ...$addends The <b>addends</b> to add
	 *
	 * @return float|int The sum of the <b>addends</b>.
	 */
	public function add(float ...$addends): float|int {
		return $this->operation(Operation::ADDITION, ...$addends);
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
		return $this->operation(Operation::SUBTRACTION, $minuend, ...$subtrahends);
	}

	public function multiply(float $multiplier, float ...$multiplicands): float|int {
		return $this->operation(Operation::MULTIPLICATION, $multiplier, ...$multiplicands);
	}

	public function divide(float $dividend, float ...$divisors): float|int {
		return $this->operation(Operation::DIVISION, $dividend, ...$divisors);
	}

	public function power(float $base, float $exponent): float|int {
		return $this->operation(Operation::POWER, $base, $exponent);
	}

	public function root(float $index, float $radicand): float|int {
		return $this->operation(Operation::ROOT, $index, $radicand);
	}

	public function log(float $anti_log, float $base): float|int {
		return $this->operation(Operation::LOGARITHM, $anti_log, $base);
	}

}
