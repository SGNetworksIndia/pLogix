<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\pLogix\Maths\Algebra;

enum AlgebraicOperation {
	case GCD;
	case LCM;
	case FACTOR;
	case DISCRIMINANT;
	case LINEAR_EQUATION;
	case QUADRATIC_EQUATION;
	case CUBIC_EQUATION;
	case QUARTIC_EQUATION;
}
