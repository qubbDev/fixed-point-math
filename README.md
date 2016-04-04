# Fixed Point Math

...

## Installation

To add Fixed Point Math as a local, per-project dependency to your project, simply add a dependency on `qubb/fixed-point-math` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a dependency on Fixed Point Math 0.1:

    {
        "require": {
            "qubb/fixed-point-math": ">=0.1"
        }
    }

## Using

Use
	\qubb\FixedPointMath\helpers\Rounder::ceil($value, $precision);
or
	\qubb\FixedPointMath\helpers\Rounder::floor($value, $precision);
or
	\qubb\FixedPointMath\helpers\Rounder::round($value, $precision);
	
for rounding numbers in the right direction

	$value - the string representation of the number
	$precisions - the rounding precision