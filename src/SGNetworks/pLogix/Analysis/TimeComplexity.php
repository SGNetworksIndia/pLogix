<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\pLogix\Analysis;

class TimeComplexity {
	public string|false $format = false;
	private float $start = 0, $end = 0, $input_size = 0;
	private string $complexity;
	private bool $marked = false;

	public bool $useLatex = true;

	public function mark(int $input_size = 0): void {
		if(!$this->marked) {
			$this->start = hrtime(true);
			$this->input_size = $input_size;
			$this->marked = true;
		} else {
			$this->end = (hrtime(true) - $this->start)/1e+6; // 1e+6 can be read as 1 times 10 raised to the power of 6, which equals 1,000,000
			$this->marked = false;
		}
	}

	public function getTime(): float {
		$this->marked = false;
		return ($this->format) ? (float) number_format($this->end, 15, '.', null) : $this->end;
	}

	public function getTimeComplexity(int $input_size = null, float $execution_time = null): string {
		$this->marked = false;
		$input_size = $input_size ?? $this->input_size;
		$execution_time = $execution_time ?? $this->end;
		// You can define your own rules to classify time complexities based on execution time and input size.
		// Here, we use a simple classification based on execution time
		$input_size_threshold = 1000;

		if ($execution_time < 0.001) {
			$this->complexity = "O(1)"; // Constant time
		} elseif ($execution_time < 0.01) {
			$this->complexity = "O(log n)"; // Logarithmic time
		} elseif ($execution_time < 1 && $input_size < $input_size_threshold) {
			$this->complexity = "O(n)"; // Linear time
		} elseif ($execution_time < 10 && $input_size < $input_size_threshold) {
			$this->complexity = "O(n log n)"; // Linearithmic time
		} elseif ($execution_time < 60 && $input_size < $input_size_threshold) {
			$this->complexity = "O(n²)"; // Quadratic time
		} else {
			$this->complexity = "O(nᵏ), k > 2"; // Polynomial time
		}

		return $this->complexity;
	}

	public function calculateComplexityOfIteration(int $inputSize, int $iterations): string {
		// Time complexity is usually represented using Big O notation

		// Constant time complexity: O(1)
		if($iterations == 1) {
			$timeComplexity = "O(1)";
		} // Linear time complexity: O(n)
		elseif($iterations == $inputSize) {
			$timeComplexity = "O(n)";
		} // Quadratic time complexity: O(n^2)
		elseif($iterations == $inputSize * $inputSize) {
			$timeComplexity = "O(n^2)";
		} // Cubic time complexity: O(n^3)
		elseif($iterations == $inputSize * $inputSize * $inputSize) {
			$timeComplexity = "O(n^3)";
		} // Polynomial time complexity: O(n^k) where k > 3
		elseif($iterations > pow($inputSize, 3) && $iterations <= pow($inputSize, 4)) {
			$timeComplexity = "O(n^k) where k > 3";
		} // Exponential time complexity: O(2^n)
		elseif($iterations == pow(2, $inputSize)) {
			$timeComplexity = "O(2^n)";
		} // Factorial time complexity: O(n!)
		elseif($iterations == $this->factorial($inputSize)) {
			$timeComplexity = "O(n!)";
		} // Logarithmic time complexity: O(log n)
		elseif($iterations == log($inputSize, 2)) {
			$timeComplexity = "O(log n)";
		} // Linearithmic time complexity: O(n log n)
		elseif($iterations == $inputSize * log($inputSize, 2)) {
			$timeComplexity = "O(n log n)";
		} // Sublinear time complexity: O(log log n)
		elseif($iterations == log(log($inputSize, 2), 2)) {
			$timeComplexity = "O(log log n)";
		} // Cubic logarithmic time complexity: O(n^3 log n)
		elseif($iterations == pow($inputSize, 3) * log($inputSize, 2)) {
			$timeComplexity = "O(n^3 log n)";
		} // Any other polynomial time complexity
		elseif($iterations > pow($inputSize, 4)) {
			$timeComplexity = "O(n^k) where k > 4";
		} // Any other exponential time complexity
		elseif($iterations > pow(2, $inputSize)) {
			$timeComplexity = "O(x^n) where x > 2";
		} // Check if the number of iterations is less than the double exponential of the input size
		elseif($iterations <= pow(2, pow(2, $inputSize))) {
			$timeComplexity = "O(2^(2^n))";
		} // Check if the number of iterations is less than the triple exponential of the input size
		elseif($iterations <= pow(2, pow(2, pow(2, $inputSize)))) {
			$timeComplexity = "O(2^(2^(2^n)))";
		} // Check if the number of iterations is less than the tetration of the input size
		elseif($iterations <= $this->tetration($inputSize, 2)) {
			$timeComplexity = "O(^n)";
		} // Add more conditions for other time complexities as needed
		else {
			$timeComplexity = "Complex";
		}

		return $timeComplexity;
	}

	public function getColorForComplexity(string $complexity = null): string {
		$this->marked = false;
		$complexity = $complexity ?? $this->complexity;

		return match ($complexity) {
			"O(1)" => "hsla(120, 100%, 50%, 1)", // Green
			"O(log n)" => "hsla(90, 100%, 50%, 1)", // Light green
			"O(n)" => "hsla(60, 100%, 50%, 1)", // Yellow
			"O(n log n)" => "hsla(30, 100%, 50%, 1)", // Orange
			"O(n²)" => "hsla(354, 80%, 34%, 1)", // Ruby Red
			"O(nᵏ), k > 2" => "hsla(0, 100%, 50%, 1)", // Red
			default => "hsla(0, 0%, 0%, 1)", // Black
		};
	}

	// Function to calculate factorial
	public function factorial(float $n): float|int {
		if($n == 0 || $n == 1) {
			return 1;
		} else {
			return $n * $this->factorial($n - 1);
		}
	}

	// Function to calculate tetration
	public function tetration(float $base, int $exponent): float|int {
		if($exponent == 1) {
			return $base;
		} else {
			return pow($base, $this->tetration($base, $exponent - 1));
		}
	}

}