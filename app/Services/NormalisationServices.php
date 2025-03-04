<?php

namespace App\Services;
use Carbon\Carbon;

class NormalisationServices
{

    /**
     * Normalise people and company names
     * Capitalise first letters, handling common exceptions eg Mc, Mac
     * Do not remove any existing capitalisations
     * @param string|null $value
     * @return string
     */
    public static function tidyName(string|null $value) : string
    {

        if (empty($value)) {
            return '';
        }

        $value = join(" ", array_map('ucfirst', explode(" ", $value)));
        $value = join("'", array_map('ucfirst', explode("'", $value)));
        $value = join("-", array_map('ucfirst', explode("-", $value)));
        $value = join("Mac", array_map('ucfirst', explode("Mac", $value)));
        $value = join("Mc", array_map('ucfirst', explode("Mc", $value)));

        return $value;
    }


    /**
     * Convert a Date string to ISO format e.g. 2017-06-20 00:00:00
     * @param string|null $value
     * @return string
     */
    public static function toISODate(string|null $value) : string
    {
        if (empty($value))
            return '';

        return Carbon::create($value)->toISOString();

    }


    /**
     * Convert a Time string to 24-hour format e.g. 17:12:45
     * @param string|null $value
     * @param string $outputFormat default H:i:s
     * @return string
     */
    public static function toTime(string|null $value, string $outputFormat='H:i:s') : string
    {
        if (empty($value))
            return '';

        return Carbon::createFromTimeString($value)->format($outputFormat);

    }


    /**
     * Convert a string to currency format
     * @param string|null $value
     * @return string
     */
    public static function toCurrency(string|null $value) : string
    {

        if (empty($value))
            return '0.00';

        return number_format( (float) $value, 2);

    }


    // Convert a comma separated string to json
    public static function toJson(string|null $value) : string
    {
        if (empty($value))
            return 'null';

        // split on commas
        $array = explode(",", $value);

        // trim any whitespace
        foreach ($array as &$item) {
            $item = trim($item);
        }

        // return a json string
        return json_encode($array);

    }


    /**
     * Remove any unnecessary white space from beginning, middle or end of a string
     * @param string|null $value
     * @return string
     */
    public static function tidyWhiteSpace(string|null $value) : string
    {
        if (empty($value))
            return '';

        return preg_replace("/\s\s+/", " ", trim($value));
    }


}
