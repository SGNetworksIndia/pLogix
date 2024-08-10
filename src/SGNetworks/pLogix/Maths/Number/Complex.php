<?php

namespace SGNetworks\pLogix\Maths\Number;

use SGNetworks\pLogix\Maths\Functions\Special;
use SGNetworks\pLogix\Maths\Exception\BadDataException;
use SGNetworks\pLogix\Maths\Exception\BadParameterException;
use SGNetworks\pLogix\Maths\Exception\IncorrectTypeException;
use function abs;
use function sin;
use function cos;
use function exp;
use function sqrt;
use function atan2;
use function is_numeric;

/**
 * Complex Numbers
 *
 * A complex number is a number that can be expressed in the form a + bi,
 * where a and b are real numbers and i is the imaginary unit, satisfying the
 * equation i² = −1. In this expression, a is the real part and b is the imaginary
 * part of the complex number.
 * https://en.wikipedia.org/wiki/Complex_number
 *
 * @property-read int|float $r
 * @property-read int|float $i
 */
class Complex implements ObjectArithmetic {
	/**
	 * Real part of the complex number
	 *
	 * @var int|float
	 */
	protected int|float $r;

	/**
	 * Imaginary part fo the complex number
	 *
	 * @var int|float
	 */
	protected int|float $i;

	/**
	 * Floating-point range near zero to consider insignificant.
	 */
	private const EPSILON = 1e-6;
	private ?int $precision;
	private bool $useLatex;

	/**
	 * Constructor
	 *
	 * @param float|int $r Real part
	 * @param float|int $i Imaginary part
	 */
	public function __construct(float|int $r, float|int $i, int $precision = null, bool $useLatex = false) {
		$this->precision = $precision;
		$this->r = $r;
		$this->i = ($this->precision != null) ? $this->round($i) : $i;
		$this->useLatex = $useLatex;
	}

	private function round(float $n): float {
		return (float)number_format($n, $this->precision, null, null);
	}

	/**
	 * Creates 0 + 0i
	 *
	 * @return Complex
	 */
	public function createZeroValue(): ObjectArithmetic {
		return new Complex(0, 0, $this->precision, $this->useLatex);
	}

	/**
	 * String representation of a complex number
	 * a + bi, a - bi, etc.
	 *
	 * @return string
	 */
	public function __toString(): string {
		if($this->r == 0 & $this->i == 0) {
			return '0';
		} elseif($this->r == 0) {
			return "$this->i" . (($this->useLatex) ? '𝚤' : 'i');
		} elseif($this->i == 0) {
			return "$this->r";
		} elseif($this->i > 0) {
			return "$this->r + $this->i" . (($this->useLatex) ? '𝚤' : 'i');
		} else {
			return "$this->r - " . abs($this->i) . (($this->useLatex) ? '𝚤' : 'i');
		}
	}

	/**
	 * Get r or i
	 *
	 * @param string $part
	 *
	 * @return int|float
	 *
	 * @throws BadParameterException if something other than r or i is attempted
	 */
	public function __get(string $part) {
		return match ($part) {
			'r', 'i', '𝚤' => $this->$part,
			default => throw new BadParameterException("The $part property does not exist in Complex number"),
		};
	}

	/**************************************************************************
	 * UNARY FUNCTIONS
	 **************************************************************************/

	/**
	 * The conjugate of a complex number
	 *
	 * https://en.wikipedia.org/wiki/Complex_number#Conjugate
	 *
	 * @return Complex
	 */
	public function complexConjugate(): Complex {
		return new Complex($this->r, -1 * $this->i, $this->precision, $this->useLatex);
	}

	/**
	 * The absolute value (magnitude) of a complex number (modulus)
	 * https://en.wikipedia.org/wiki/Complex_number#Absolute_value_and_argument
	 *
	 * If z = a + bi
	 *        _______
	 * |z| = √a² + b²
	 *
	 * @return int|float
	 */
	public function abs(): float|int {
		return sqrt($this->r ** 2 + $this->i ** 2);
	}

	/**
	 * The argument (phase) of a complex number
	 * The argument of z is the angle of the radius OP with the positive real axis, and is written as arg(z).
	 * https://en.wikipedia.org/wiki/Complex_number#Absolute_value_and_argument
	 *
	 * If z = a + bi
	 * arg(z) = atan(b, a)
	 *
	 * @return int|float
	 */
	public function arg(): float|int {
		return atan2($this->i, $this->r);
	}

	/**
	 * The square root of a complex number
	 * https://en.wikipedia.org/wiki/Complex_number#Square_root
	 *
	 * The roots of a + bi (with b ≠ 0) are ±(γ + δi), where
	 *
	 *         ____________
	 *        /     _______
	 *       / a + √a² + b²
	 * γ =  /  ------------
	 *     √         2
	 *
	 *               ______________
	 *              /______________
	 *             / -a + √a² + b²
	 * δ = sgn(b) /----------------
	 *           √         2
	 *
	 * The square root returns the positive root.
	 *
	 * @return Complex (positive root)
	 */
	public function sqrt(): Complex {
		return $this->roots()[0];
	}

	/**
	 * The roots of a complex number
	 * https://en.wikipedia.org/wiki/Complex_number#Square_root
	 *
	 * The roots of a + bi (with b ≠ 0) are ±(γ + δi), where
	 *
	 *         ____________
	 *        /     _______
	 *       / a + √a² + b²
	 * γ =  /  ------------
	 *     √         2
	 *
	 *               ____________
	 *              /      _______
	 *             / -a + √a² + b²
	 * δ = sgn(b) /  -------------
	 *           √         2
	 *
	 *
	 * @return array{Complex, Complex} (two roots)
	 */
	public function roots(): array {
		$sgn = Special::sgn($this->i) >= 0 ? 1 : -1;
		$gamma = sqrt(($this->r + $this->abs()) / 2);
		$omega = $sgn * sqrt((-$this->r + $this->abs()) / 2);

		$z1 = new Complex($gamma, $omega, $this->precision, $this->useLatex);
		$z2 = new Complex(-$gamma, -$omega, $this->precision, $this->useLatex);

		return [$z1, $z2];
	}

	/**
	 * The inverse of a complex number (reciprocal)
	 *
	 * https://en.wikipedia.org/wiki/Complex_number#Reciprocal
	 *
	 * @return Complex
	 *
	 * @throws BadDataException|IncorrectTypeException if = to 0 + 0i
	 */
	public function inverse(): Complex {
		if($this->r == 0 && $this->i == 0) {
			throw new BadDataException('Cannot take inverse of 0 + 0i');
		}

		return $this->complexConjugate()->divide($this->abs() ** 2);
	}

	/**
	 * Negate the complex number
	 * Switches the signs of both the real and imaginary parts.
	 *
	 * @return Complex
	 */
	public function negate(): Complex {
		return new Complex(-$this->r, -$this->i, $this->precision, $this->useLatex);
	}

	/**
	 * Polar form
	 * https://en.wikipedia.org/wiki/Complex_number#Polar_form
	 *
	 * z = a + bi = r(cos(θ) + i  sin(θ))
	 * Where
	 *  r = |z|
	 *  θ = arg(z) (in radians)
	 *
	 * @return int[]|float[]
	 */
	public function polarForm(): array {
		$r = $this->abs();
		$theta = $this->arg();

		return [$r, $theta];
	}

	/**
	 * Complex Exponentiation
	 * https://en.wikipedia.org/wiki/Complex_number#Exponential_function
	 *
	 * eˣ⁺ⁱʸ = eˣ*cos(y) + i*eˣ*sin(y)
	 *
	 * @return Complex
	 */
	public function exp(): Complex {
		$r = exp($this->r) * cos($this->i);
		$i = exp($this->r) * sin($this->i);
		return new Complex($r, $i, $this->precision, $this->useLatex);
	}
	/**************************************************************************
	 * BINARY FUNCTIONS
	 **************************************************************************/

	/**
	 * Complex addition
	 * https://en.wikipedia.org/wiki/Complex_number#Addition_and_subtraction
	 *
	 * (a + bi) + (c + di) = (a + c) + (b + d)i
	 *
	 * @param mixed $object_or_scalar
	 *
	 * @return Complex
	 *
	 * @throws IncorrectTypeException if the argument is not numeric or Complex.
	 */
	public function add(mixed $object_or_scalar): Complex {
		if(is_numeric($object_or_scalar)) {
			$r = $this->r + $object_or_scalar;
			$i = $this->i;
		} elseif($object_or_scalar instanceof Complex) {
			$r = $this->r + $object_or_scalar->r;
			$i = $this->i + $object_or_scalar->i;
		} else {
			throw new IncorrectTypeException('Argument must be real or complex number');
		}

		return new Complex($r, $i, $this->precision, $this->useLatex);
	}

	/**
	 * Complex subtraction
	 * https://en.wikipedia.org/wiki/Complex_number#Addition_and_subtraction
	 *
	 * (a + bi) - (c + di) = (a - c) + (b - d)i
	 *
	 * @param mixed $object_or_scalar
	 *
	 * @return Complex
	 *
	 * @throws IncorrectTypeException if the argument is not numeric or Complex.
	 */
	public function subtract(mixed $object_or_scalar): Complex {
		if(is_numeric($object_or_scalar)) {
			$r = $this->r - $object_or_scalar;
			$i = $this->i;
		} elseif($object_or_scalar instanceof Complex) {
			$r = $this->r - $object_or_scalar->r;
			$i = $this->i - $object_or_scalar->i;
		} else {
			throw new IncorrectTypeException('Argument must be real or complex number');
		}

		return new Complex($r, $i, $this->precision, $this->useLatex);
	}

	/**
	 * Complex multiplication
	 * https://en.wikipedia.org/wiki/Complex_number#Multiplication_and_division
	 *
	 * (a + bi)(c + di) = (ac - bd) + (bc + ad)i
	 *
	 * @param mixed $object_or_scalar
	 *
	 * @return Complex
	 *
	 * @throws IncorrectTypeException if the argument is not numeric or Complex.
	 */
	public function multiply(mixed $object_or_scalar): Complex {
		if(is_numeric($object_or_scalar)) {
			$r = $object_or_scalar * $this->r;
			$i = $object_or_scalar * $this->i;
		} elseif($object_or_scalar instanceof Complex) {
			$r = $this->r * $object_or_scalar->r - $this->i * $object_or_scalar->i;
			$i = $this->i * $object_or_scalar->r + $this->r * $object_or_scalar->i;
		} else {
			throw new IncorrectTypeException('Argument must be real or complex number');
		}

		return new Complex($r, $i, $this->precision, $this->useLatex);
	}

	/**
	 * Complex division
	 * Dividing two complex numbers is accomplished by multiplying the first by the inverse of the second
	 * https://en.wikipedia.org/wiki/Complex_number#Multiplication_and_division
	 *
	 * @param mixed $c
	 *
	 * @return Complex
	 *
	 * @throws IncorrectTypeException|BadDataException if the argument is not numeric or Complex.
	 */
	public function divide(mixed $c): Complex {
		if(is_numeric($c)) {
			$r = $this->r / $c;
			$i = $this->i / $c;
			return new Complex($r, $i, $this->precision, $this->useLatex);
		} elseif($c instanceof Complex) {
			return $this->multiply($c->inverse());
		} else {
			throw new IncorrectTypeException('Argument must be real or complex number');
		}
	}

	/**
	 * Complex exponentiation.
	 * Raise a complex number to a power.
	 *  - https://en.wikipedia.org/wiki/Complex_number#Exponentiation
	 *  - https://mathworld.wolfram.com/ComplexExponentiation.html
	 *
	 * @param float|int|Complex $c
	 *
	 * @return Complex
	 *
	 * @throws IncorrectTypeException if the argument is not numeric or Complex.
	 */
	public function pow(float|int|Complex $c): Complex {
		if(is_numeric($c)) {
			$tmp = new Complex(0, $c * $this->arg(), $this->precision, $this->useLatex);
			return $tmp->exp()->multiply($this->abs() ** $c);
		}

		if($c instanceof Complex) {
			$r = $this->abs();
			$theta = $this->arg();
			$real = $r ** $c->r * exp(-1 * $theta * $c->i);
			$inner = $r == 0 ? 0 : $c->i * log($r) + $c->r * $theta;
			$new_r = $real * cos($inner);
			$new_i = $real * sin($inner);
			return new Complex($new_r, $new_i, $this->precision, $this->useLatex);
		}

		throw new IncorrectTypeException('Argument must be real or complex number');
	}

	/**************************************************************************
	 * COMPARISON FUNCTIONS
	 **************************************************************************/

	/**
	 * Test for equality
	 * Two complex numbers are equal if and only if both their real and imaginary parts are equal.
	 *
	 * https://en.wikipedia.org/wiki/Complex_number#Equality
	 *
	 * @param Complex $c
	 *
	 * @return bool
	 */
	public function equals(Complex $c): bool {
		return abs($this->r - $c->r) < self::EPSILON && abs($this->i - $c->i) < self::EPSILON;
	}
}
