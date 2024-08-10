<?php

namespace SGNetworks\pLogix\Maths\Number;

interface ObjectArithmetic {
	/**
	 * Add two objects together
	 *
	 * @param mixed $object_or_scalar the value to be added
	 *
	 * @return ObjectArithmetic sum.
	 */
	public function add(int|Rational $object_or_scalar): ObjectArithmetic;

	/**
	 * Subtract one objects from another
	 *
	 * @param mixed $object_or_scalar the value to be subtracted
	 *
	 * @return ObjectArithmetic result.
	 */
	public function subtract(int|Rational $object_or_scalar): ObjectArithmetic;

	/**
	 * Multiply two objects together
	 *
	 * @param mixed $object_or_scalar value to be multiplied
	 *
	 * @return ObjectArithmetic product.
	 */
	public function multiply(int|Rational $object_or_scalar): ObjectArithmetic;

	/**
	 * Factory method to create the zero value of the object
	 *
	 * @return ObjectArithmetic
	 */
	public function createZeroValue(): ObjectArithmetic;
}
