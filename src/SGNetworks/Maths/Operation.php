<?php
/*
 * Copyright (c) 2024 SGNetworks. All rights reserved.
 *
 * The software is an exclusive copyright of "SGNetworks" and is provided as is exclusively with only "USAGE" access. "Modification",  "Alteration", "Re-distribution" is completely prohibited.
 * VIOLATING THE ABOVE TERMS IS A PUNISHABLE OFFENSE WHICH MAY LEAD TO LEGAL CONSEQUENCES.
 */

namespace SGNetworks\Maths;

enum Operation {
	case ADDITION;
	case SUBTRACTION;
	case DIVISION;
	case MULTIPLICATION;
	case POWER;
	case ROOT;
	case LOGARITHM;
}
