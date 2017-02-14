<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Tools;

/**
 * Class JsonDataType
 *
 * Helper for data types
 *
 * @package app\Tools
 */
class JsonDataType
{

    /**
     * Decodes a JSON string
     *
     * @param string $string
     * @param bool $asArray
     * @param int $depth
     * @param int $options
     *
     * @throws \InvalidArgumentException in case of error
     *
     * @return mixed
     */
    public static function decodeJSON(string $string, bool $asArray = false, int $depth = 512, int $options = 0)
    {

        if (empty($string)) {
            return [];
        }

        // decode the JSON data
        $result = json_decode($string, $asArray, $depth, $options);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            throw new \InvalidArgumentException('JSON error: ' . $error . PHP_EOL . PHP_EOL . 'String: ' . $string);
        }

        // everything is OK
        return $result;
    }

    /**
     * Checks validity of a JSON string
     *
     * @param mixed $json
     *
     * @return bool
     */
    public static function isValidJSON($json) : bool
    {

        if (!is_string($json)) {
            return false;
        }

        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }
}