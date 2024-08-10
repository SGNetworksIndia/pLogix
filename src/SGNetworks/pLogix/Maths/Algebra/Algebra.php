<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\pLogix\Maths\Algebra;

use SGNetworks\pLogix\Maths\Number;
use SGNetworks\pLogix\Maths\Exception;
use SGNetworks\pLogix\Maths\Number\Complex;
use SGNetworks\pLogix\Maths\Functions\Map\Single;
use SGNetworks\pLogix\Maths\Arithmetics\Arithmetics;
use function abs;
use function cos;
use function sqrt;
use function acos;
use function sort;
use function next;
use function count;
use function rsort;
use function reset;
use function is_float;
use function array_merge;
use function array_product;
use function array_count_values;
use const INF;
use const NAN;
use const M_PI;

class Algebra extends Arithmetics {
	private ?int $precision;

	/**
	 * @param int|null $precision
	 * @param bool $useLatex
	 */
	public function __construct(?int $precision = null, bool $useLatex = false) {
		$this->precision = $precision;
		$this->useLatex = $useLatex;
	}

	/**
	 * Greatest Common Divisor - recursive Euclid's algorithm.<br>
	 * The largest positive integer that divides the numbers without a remainder.<br>
	 * For example, the GCD of 8 and 12 is 4.
	 *
	 * Gcd(a, 0) = a
	 * gcd(a, b) = gcd(b, a mod b)
	 *
	 * @param int $x
	 * @param int $y
	 *
	 * @return int
	 * @see https://en.wikipedia.org/wiki/Greatest_common_divisor
	 *
	 */
	public function gcd(int $x, int $y): int {
		return $this->operation(AlgebraicOperation::GCD, $x, $y);
	}

	/**
	 * Least Common Multiple<br>
	 * The smallest positive integer that is divisible by both a and b.<br>
	 * For example, the LCM of 5 and 2 is 10.<br>
	 *
	 *              |a ⋅ b|
	 * lcm(a, b) = ---------
	 *             gcd(a, b)
	 *
	 * @param int $x
	 * @param int $y
	 *
	 * @return int
	 * @psalm-suppress InvalidReturnType (Change to intdiv for PHP 8.0)
	 *
	 * @see https://en.wikipedia.org/wiki/Least_common_multiple
	 */
	public function lcm(int $x, int $y): int {
		return $this->operation(AlgebraicOperation::LCM, $x, $y);
	}

	/**
	 * Get factors of an integer
	 * The decomposition of a composite number into a product of smaller integers.
	 *
	 * Algorithm:
	 * - special case: if x is 0, return [\INF]
	 * - let x be |x|
	 * - push on 1 as a factor
	 * - prime factorize x
	 * - build sets of prime powers from primes
	 * - push on the product of each set
	 *
	 * @param int $x
	 *
	 * @return array<float> of factors
	 *
	 *
	 * @see https://en.wikipedia.org/wiki/Integer_factorization
	 */
	public function factor(int $x): array {
		return $this->operation(AlgebraicOperation::FACTOR, $x);
	}

	/**
	 * Discriminant
	 * https://en.wikipedia.org/wiki/Discriminant
	 *
	 * Δ = b² - 4ac
	 *
	 * @param float $a x² coefficient
	 * @param float $b x coefficient
	 * @param float $c constant coefficient
	 *
	 * @return float
	 */
	public function discriminant(float $a, float $b, float $c): float {
		return $this->operation(AlgebraicOperation::DISCRIMINANT, $a, $b, $c);
	}

	/**
	 * Linear equation of one variable
	 * An equation having the form: ax + b = 0
	 * where x represents an unknown, or the root of the equation, and a and b represent known numbers.
	 * https://en.wikipedia.org/wiki/Linear_equation#One_variable
	 *
	 * ax + b = 0
	 *
	 *     -b
	 * x = --
	 *      a
	 *
	 * No root exists for a = 0, as a(0) + b = b
	 *
	 * @param float $a a of ax + b = 0
	 * @param float $b b of ax + b = 0
	 *
	 * @return float|null Root of the linear equation: x = -b / a
	 */
	public function linear(float $a, float $b): ?float {
		return $this->operation(AlgebraicOperation::LINEAR_EQUATION, $a, $b);
	}

	/**
	 * Quadratic equation
	 * An equation having the form: ax² + bx + c = 0
	 * where x represents an unknown, or the root(s) of the equation,
	 * and a, b, and c represent known numbers such that a is not equal to 0.
	 * The numbers a, b, and c are the coefficients of the equation
	 * https://en.wikipedia.org/wiki/Quadratic_equation
	 *
	 *           _______
	 *     -b ± √b² -4ac
	 * x = -------------
	 *           2a
	 *
	 * Edge case where a = 0 and formula is not quadratic:
	 *
	 * 0x² + bx + c = 0
	 *
	 *     -c
	 * x = ---
	 *      b
	 *
	 * Note: If discriminant is negative, roots will be NAN.
	 *
	 * @param float $a x² coefficient
	 * @param float $b x coefficient
	 * @param float $c constant coefficient
	 * @param bool $return_complex Whether to return complex numbers or NANs if imaginary roots
	 *
	 * @return float[]|Complex[]
	 *      [x₁, x₂]           roots of the equation, or
	 *      [NAN, NAN]         if discriminant is negative, or
	 *      [Complex, Complex] if discriminant is negative and complex option is on or
	 *      [x]                if a = 0 and formula isn't quadratics
	 *
	 */
	public function quadratic(float $a, float $b, float $c, bool $return_complex = false): array {
		return $this->operation(AlgebraicOperation::QUADRATIC_EQUATION, $a, $b, $c, $return_complex);
	}

	/**
	 * Cubic equation
	 * An equation having the form: z³ + a₂z² + a₁z + a₀ = 0
	 * https://en.wikipedia.org/wiki/Cubic_function
	 * http://mathworld.wolfram.com/CubicFormula.html
	 *
	 * The coefficient a₃ of z³ may be taken as 1 without loss of generality by dividing the entire equation through by a₃.
	 *
	 * If a₃ ≠ 0, then divide a₂, a₁, and a₀ by a₃.
	 *
	 *     3a₁ - a₂²
	 * Q ≡ ---------
	 *         9
	 *
	 *     9a₂a₁ - 27a₀ - 2a₂³
	 * R ≡ -------------------
	 *             54
	 *
	 * Polynomial discriminant D
	 * D ≡ Q³ + R²
	 *
	 * If D > 0, one root is real, and two are are complex conjugates.
	 * If D = 0, all roots are real, and at least two are equal.
	 * If D < 0, all roots are real and unequal.
	 *
	 * If D < 0:
	 *
	 *                    R
	 * Define θ = cos⁻¹  ----
	 *                   √-Q³
	 *
	 * Then the real roots are:
	 *
	 *        __      /θ\
	 * z₁ = 2√-Q cos | - | - ⅓a₂
	 *                \3/
	 *
	 *        __      /θ + 2π\
	 * z₂ = 2√-Q cos | ------ | - ⅓a₂
	 *                \   3  /
	 *
	 *        __      /θ + 4π\
	 * z₃ = 2√-Q cos | ------ | - ⅓a₂
	 *                \   3  /
	 *
	 * If D = 0 or D > 0:
	 *       ______
	 * S ≡ ³√R + √D
	 *       ______
	 * T ≡ ³√R - √D
	 *
	 * If D = 0:
	 *
	 *      -a₂   S + T
	 * z₁ = --- - -----
	 *       3      2
	 *
	 *      S + T - a₂
	 * z₂ = ----------
	 *           3
	 *
	 *      -a₂   S + T
	 * z₃ = --- - -----
	 *       3      2
	 *
	 * If D > 0:
	 *
	 *      S + T - a₂
	 * z₁ = ----------
	 *           3
	 *
	 * z₂ = Complex conjugate; therefore, NAN
	 * z₃ = Complex conjugate; therefore, NAN
	 *
	 * @param float $a z³         coefficient
	 * @param float $b z²         coefficient
	 * @param float $c z          coefficient
	 * @param float $d constant coefficient
	 * @param bool $return_complex whether to return complex numbers
	 *
	 * @return float[]|Complex[]
	 *      array of roots (three real roots, or one real root and two NANs because complex numbers not yet supported)
	 *      (If $a₃ = 0, then only two roots of quadratic equation)
	 *
	 */
	public function cubic(float $a, float $b, float $c, float $d, bool $return_complex = false): array {
		return $this->operation(AlgebraicOperation::CUBIC_EQUATION, $a, $b, $c, $d, $return_complex);
	}

	/**
	 * Quartic equation
	 * An equation having the form: a₄z⁴ + a₃z³ + a₂z² + a₁z + a₀ = 0
	 *
	 * Sometimes this is referred to as a biquadratic equation.
	 *
	 * @param float $a z⁴          coefficient
	 * @param float $b z³          coefficient
	 * @param float $c z²          coefficient
	 * @param float $d z           coefficient
	 * @param float $e constant coefficient
	 * @param bool $return_complex whether to return complex numbers
	 *
	 * @return float[]|Complex[] array of roots
	 *
	 * @see https://en.wikipedia.org/wiki/Quartic_function
	 */
	public function quartic(float $a, float $b, float $c, float $d, float $e, bool $return_complex = false): array {
		return $this->operation(AlgebraicOperation::QUARTIC_EQUATION, $a, $b, $c, $d, $e, $return_complex);
	}

	private function operation(AlgebraicOperation $operation, float|int ...$args): float|int|array|null {
		$this->mark(count($args));

		switch($operation) {
			case AlgebraicOperation::GCD:
				$x = $args[0];
				$y = $args[1];

				// Base cases
				if($x == 0) {
					$gcd = $y;
				} elseif($y == 0) {
					$gcd = $x;
				} else {
					// Recursive case
					$gcd = $this->operation(AlgebraicOperation::GCD, $y, $x % $y);
				}
				$this->mark();
				return $gcd;
			case AlgebraicOperation::LCM:
				$x = $args[0];
				$y = $args[1];

				// Special case
				if($x === 0 || $y === 0) {
					$lcm = 0;
				} else {
					$lcm = abs($x * $y) / $this->operation(AlgebraicOperation::GCD, $x, $y);
				}
				$this->mark();
				return $lcm;
			case AlgebraicOperation::FACTOR:
				$x = $args[0];

				// 0 has infinite factors
				if($x === 0) {
					$factors = [INF];
				} else {
					$x = abs($x);
					$factors = [1];

					// Prime factorize x
					$primes = $this->primeFactorization($x);

					// Prime powers from primes
					$sets = [];
					$current = [];
					$map = [];
					$exponents = array_count_values($primes);
					$limit = 1;
					$count = 0;

					foreach($exponents as $prime => $exponent) {
						$map[] = $prime;
						$sets[$prime] = [1, $prime];
						$primePower = $prime;

						for($n = 2; $n <= $exponent; ++$n) {
							$primePower *= $prime;
							$sets[$prime][$n] = $primePower;
						}

						$limit *= count($sets[$prime]);
						if($count === 0) { // Skip 1 on the first prime
							$current[] = next($sets[$prime]);
						} else {
							$current[] = 1;
						}
						++$count;
					}

					// Multiply distinct prime powers together
					for($i = 1; $i < $limit; ++$i) {
						$factors[] = array_product($current);
						for($i2 = 0; $i2 < $count; ++$i2) {
							$current[$i2] = next($sets[$map[$i2]]);
							if($current[$i2] !== false) {
								break;
							}
							$current[$i2] = reset($sets[$map[$i2]]);
						}
					}

					sort($factors);
				}

				$this->mark();
				return $factors;
			case AlgebraicOperation::DISCRIMINANT:
				$a = $args[0];
				$b = $args[1];
				$c = $args[2];

				$discriminant = $b ** 2 - (4 * $a * $c);

				$this->mark();
				return $discriminant;
			case AlgebraicOperation::LINEAR_EQUATION:
				$a = $args[0];
				$b = $args[1];

				$leq = ($a == 0) ? null : -$b / $a;

				$this->mark();
				return $leq;
			case AlgebraicOperation::QUADRATIC_EQUATION:
				$a = $args[0];
				$b = $args[1];
				$c = $args[2];
				$return_complex = $args[3];

				// Formula not quadratic (a = 0)
				if($a == 0) {
					$qeq = [-$c / $b];
				} else {
					// Discriminant intermediate calculation and imaginary number check
					$discriminant = $this->operation(AlgebraicOperation::DISCRIMINANT, $a, $b, $c);
					if($discriminant < 0) {
						$x1 = -$b / (2 * $a);
						$x2 = sqrt(-$discriminant) / (2 * $a);

						if(!$return_complex) {
							$x1 = NAN;
							$x2 = NAN;
						} else {
							$x1 = new Complex(0,$x1,$this->precision, $this->useLatex);
							$x2 = new Complex(0,$x2,$this->precision, $this->useLatex);
							/*$complex = new Complex(0, sqrt(-1 * $discriminant),$this->precision, $this->useLatex);
							$x1 = $complex->multiply(-1)->subtract($b)->divide(2 * $a);
							$x2 = $complex->subtract($b)->divide(2 * $a);*/
						}
					} else {
						// Standard quadratic equation case
						$qe = sqrt($this->operation(AlgebraicOperation::DISCRIMINANT,$a, $b, $c));
						$x1 = (-$b - $qe) / (2 * $a);
						$x2 = (-$b + $qe) / (2 * $a);

					}

					$qeq = [$x1, $x2];
				}

				$this->mark();
				return $qeq;
			case AlgebraicOperation::CUBIC_EQUATION:
				$a3 = $args[0];
				$a2 = $args[1];
				$a1 = $args[2];
				$a0 = $args[3];
				$return_complex = $args[4];

				if($a3 == 0) {
					$ceq = $this->operation(AlgebraicOperation::QUADRATIC_EQUATION, $a2, $a1, $a0, $return_complex);
				} else {
					// Take coefficient a₃ of z³ to be 1
					$a2 = $a2 / $a3;
					$a1 = $a1 / $a3;
					$a0 = $a0 / $a3;

					// Intermediate variables
					$Q = (3 * $a1 - $a2 ** 2) / 9;
					$R = (9 * $a2 * $a1 - 27 * $a0 - 2 * $a2 ** 3) / 54;

					// Polynomial discriminant
					$D = $Q ** 3 + $R ** 2;

					// All roots are real and unequal
					if($D < 0) {
						$theta = acos($R / sqrt((-$Q) ** 3));
						$sqrtQ = 2 * sqrt(-$Q);
						$pi = M_PI;

						$z1 = $sqrtQ * cos($theta / 3) - ($a2 / 3);
						$z2 = $sqrtQ * cos(($theta + 2 * $pi) / 3) - ($a2 / 3);
						$z3 = $sqrtQ * cos(($theta + 4 * $pi) / 3) - ($a2 / 3);

						$z1 = ($this->precision != null) ? round($z1, $this->precision) : $z1;
						$z2 = ($this->precision != null) ? round($z2, $this->precision) : $z2;
						$z3 = ($this->precision != null) ? round($z2, $this->precision) : $z3;

						$ceq = [$z1, $z2, $z3];
					} else {
						// Intermediate calculations
						$S = $this->root($R + sqrt($D), 3);
						$T = $this->root($R + sqrt($D), 3);

						// All roots are real, and at least two are equal
						if($D == 0 || ($D > -self::ZERO_TOLERANCE && $D < self::ZERO_TOLERANCE)) {
							$z1 = -$a2 / 3 - ($S + $T) / 2;
							$z2 = $S + $T - $a2 / 3;
							$z3 = -$a2 / 3 - ($S + $T) / 2;

							$z1 = ($this->precision != null) ? round($z1, $this->precision) : $z1;
							$z2 = ($this->precision != null) ? round($z2, $this->precision) : $z2;
							$z3 = ($this->precision != null) ? round($z2, $this->precision) : $z3;

							$ceq = [$z1, $z2, $z3];
						} else {
							// D > 0: One root is real, and two are complex conjugates
							$z1 = $S + $T - $a2 / 3;
							$z1 = ($this->precision != null) ? round($z1, $this->precision) : $z1;

							if(!$return_complex) {
								$ceq = [$z1, NAN, NAN];
							} else {
								$quad_a = 1;
								$quad_b = $a2 + $z1;
								$quad_c = $a1 + $quad_b * $z1;
								$complex_roots = $this->quadratic($quad_a, $quad_b, $quad_c, true);

								$ceq = array_merge([$z1], $complex_roots);
							}
						}
					}
				}

				$this->mark();
				return $ceq;
			case AlgebraicOperation::QUARTIC_EQUATION:
				$a4 = $args[0];
				$a3 = $args[1];
				$a2 = $args[2];
				$a1 = $args[3];
				$a0 = $args[4];
				$return_complex = $args[5];

				// Not actually quartic.
				if($a4 == 0) {
					$q5eq = $this->cubic($a3, $a2, $a1, $a0, $return_complex);
				} else {
					// Take coefficient a₄ of z⁴ to be 1
					$a3 = $a3 / $a4;
					$a2 = $a2 / $a4;
					$a1 = $a1 / $a4;
					$a0 = $a0 / $a4;
					$a4 = 1;

					// Has a zero root.
					if($a0 == 0) {
						$q5eq = array_merge([0.0], $this->operation(AlgebraicOperation::CUBIC_EQUATION,$a4, $a3, $a2, $a1, $return_complex));
					} else {
						// Is Bi-quadratic
						if($a3 == 0 && $a1 == 0) {
							$quadratic_roots = $this->operation(AlgebraicOperation::QUADRATIC_EQUATION,$a4, $a2, $a0, $return_complex);

							// Sort so any complex roots are at the end of the array.
							rsort($quadratic_roots);
							/** @var array{float, float} $quadratic_roots */
							$zp = $quadratic_roots[0];
							$zd = $quadratic_roots[1];
							if(!$return_complex) {
								$q5eq = [sqrt($zp), -1 * sqrt($zp), sqrt($zd), -1 * sqrt($zd)];
							} else {
								$CZp = new Complex($zp, 0, $this->precision, $this->useLatex);
								$Czd = new Complex($zd, 0, $this->precision, $this->useLatex);
								$z1 = $zp < 0 ? $CZp->sqrt() : sqrt($zp);
								$z2 = $zp < 0 ? $z1->negate() : $z1 * -1;
								$z3 = $zd < 0 ? $Czd->sqrt() : sqrt($zd);
								$z4 = $zd < 0 ? $z3->negate() : $z3 * -1;

								$q5eq = [$z1, $z2, $z3, $z4];
							}
						} else {
							// Is a depressed quartic
							// y⁴ + py² + qy + r = 0
							if($a3 == 0) {
								$p = $a2;
								$q = $a1;
								$r = $a0;
								// Create the resolvent cubic.
								// 8m³ + 8pm² + (2p² - 8r)m - q² = 0
								$cubic_roots = $this->operation(AlgebraicOperation::CUBIC_EQUATION,8, 8 * $p, 2 * $p ** 2 - 8 * $r, -1 * $q ** 2, $return_complex);

								// $z1 will always be a real number, so select it.
								$m = $cubic_roots[0];
								$roots1 = $this->operation(AlgebraicOperation::QUADRATIC_EQUATION,1, sqrt(2 * $m), $p / 2 + $m - $q / 2 / sqrt(2 * $m), $return_complex);
								$roots2 = $this->operation(AlgebraicOperation::QUADRATIC_EQUATION,1, -1 * sqrt(2 * $m), $p / 2 + $m + $q / 2 / sqrt(2 * $m), $return_complex);
								$discriminant1 = $this->operation(AlgebraicOperation::DISCRIMINANT,1, sqrt(2 * $m), $p / 2 + $m - $q / 2 / sqrt(2 * $m));
								$discriminant2 = $this->operation(AlgebraicOperation::DISCRIMINANT,1, -1 * sqrt(2 * $m), $p / 2 + $m + $q / 2 / sqrt(2 * $m));

								// sort the real roots first.
								$q5eq = $discriminant1 > $discriminant2
									? array_merge($roots1, $roots2)
									: array_merge($roots2, $roots1);
							} else {
								// Create the factors for a depressed quartic.
								$p = $a2 - (3 * $a3 ** 2 / 8);
								$q = $a1 + $a3 ** 3 / 8 - $a3 * $a2 / 2;
								$r = $a0 - 3 * $a3 ** 4 / 256 + $a3 ** 2 * $a2 / 16 - $a3 * $a1 / 4;

								$depressed_quartic_roots = $this->operation(AlgebraicOperation::QUARTIC_EQUATION,1, 0, $p, $q, $r, $return_complex);

								// The roots for this polynomial are the roots of the depressed polynomial minus a₃/4.
								if(!$return_complex) {
									$q5eq = Single::subtract($depressed_quartic_roots, $a3 / 4);
								} else {
									$q5eq = [];
									foreach($depressed_quartic_roots as $key => $root) {
										if(is_float($root)) {
											$q5eq[$key] = $root - $a3 / 4;
										} else {
											$q5eq[$key] = $root->subtract($a3 / 4);
										}
									}
								}
							}
						}
					}
				}

				$this->mark();
				return $q5eq;
		}

		$this->mark();

		return -1;
	}
}