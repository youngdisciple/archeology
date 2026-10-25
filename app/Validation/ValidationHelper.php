<?php

declare(strict_types=1);

namespace App\Validation;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\UnprocessableEntityException;

/**
 * A helper class that exposes various data validation functions.
 * @author frostybee
 */
class ValidationHelper
{


    /**
     * Checks whether a string contains only alphabetic characters.
     * @param mixed $value the string to be validated
     * @return mixed false if the value is invalid. Otherwise, the sanitized string will be returned.
     */
    public static function isAlpha($value): mixed
    {
        $value = filter_var(trim($value), FILTER_SANITIZE_ADD_SLASHES);
        if (ctype_alpha($value)) {
            return $value;
        }
        return false;
    }

    /**
     * Checks whether a value is an integer and is within a range.
     * @param mixed $value an input value to be validated
     * @param int $min the lower bound of the range of allowed values
     * @param int $max the upper bound of the range of allowed values
     * @return bool|array
     */
    public static function isIntAndInRange($value, int $min, int $max): mixed
    {
        return filter_var($value, FILTER_VALIDATE_INT, static::getRangeOptions($min, $max));
    }


    /**
     * Checks whether a value is a valid integer and is greater than
     * the specified min value.
     * @param mixed $input an input value to be validated
     * @return mixed bool|array
     */
    public static function isInt($input, int $min = -1): mixed
    {
        if ($min >= 0) {
            return filter_var($input, FILTER_VALIDATE_INT, self::getMinRangeOptions($min));
        }

        return filter_var($input, FILTER_VALIDATE_INT);
    }

    public static function getMinRangeOptions(int $min): array
    {
        return array("options" => array("min_range" => $min));
    }

    public static function getRangeOptions(int $min, int $max): array
    {
        return array(
            "options" =>
            array("min_range" => $min, "max_range" => $max)
        );
    }

    /**
     * Determines whether an array is associative or not.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     * Note that an array in PHP can be either sequential or associative.
     *
     * @param  array  $array the array to be verified.
     * @return bool
     */
    public static function isAssoc(array $input): bool
    {
        if (empty($input)) {
            return false;
        }
        $keys = array_keys($input);
        return array_keys($keys) !== $keys;
    }

    /**
     * Helper function to decide whether a given ID is valid
     * cannot be negative
     * must be an INTEGER
     * @param string $id
     * @return bool
     */
    public static function isValidId(string $id): bool
    {
        $pattern = "/^\d{1,9}$/";
        return (preg_match($pattern, $id) === 1);
    }

    /**
     * Helper function to decide whether the given pagination parameters are non-negative integers
     * @param mixed $current_page
     * @param mixed $page_size
     * @return bool
     */
    public static function isValidPagination(?string $current_page, ?string $page_size): bool
    {
        $pattern = "/^\d{1,5}$/";
        return preg_match($pattern, $current_page) === 1 && preg_match($pattern, $page_size) === 1;
    }

    /**
    * A function that validates the filters from a given set of rules.
    * @param Request $request
    * @param array $rules The rules parameter must take in the following shape: ["filter" (exact name) => rule (anonymous function)]
    * @return array The validated filters
    */
    public static function validateParams(Request $request, array $rules, bool $case_sensitive = false)
    {
        $filters = $request->getQueryParams();

        // check if it contains non defined filters
        // $extra = array_diff_key($filters, $rules);
        // if (!empty($extra)) {
        //     throw new UnprocessableEntityException($request, "The given query filters contain unprocessable entities");
        // }
        //
        // NOTE:
        // I removed this because of two concerns:
        // Firstly because array_diff checks differences from both sides,,,
        // so if the user DOESN'T use ALL filters, it will result in an error.
        // The right solution is to have $rules(keys) \ $filters(keys),
        // i.e., a set difference to check only the extra keys from $rules.
        // php doesn't natively have sets so this solution is of no practical
        // relevance.

        // check if any given filter doesn't comply to it's respective rule(s)

        foreach ($filters as $filter => $v) {
            // false by default
            $v = $case_sensitive ? $v : strtolower($v);

            $valid = $rules[$filter]($v) && is_string($v);
            // WARNING: Query params can either be a string or an array.
            // To my knowledge, there isn't any array parameters in our case
            // so I avoid repeating ourselves by doing the is_string check here
            // rather than in all filter rules.

            if (!$valid) {
                throw new UnprocessableEntityException(
                    $request,
                    "Invalid filter value ({$filter} => {$v})"
                );
            }
        }

        return $filters;
    }
}
