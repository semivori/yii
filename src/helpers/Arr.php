<?php


namespace helpers;


use yii\helpers\ArrayHelper;

class Arr extends \IlluminateAgnostic\Arr\Support\Arr
{
    /**
     * @param  array  $input
     * @return array
     */
    public static function trim($input)
    {
        if (is_array($input)) {
            return array_filter(
                $input,
                function (&$value) {
                    return $value = self::trim($value);
                }
            );
        }

        return $input;
    }

    /**
     * Return the key of first element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  null  $default
     * @return int|mixed|string|null
     */
    public static function firstKey($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $default;
            }

            foreach ($array as $key => $item) {
                return $key;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $key;
            }
        }

        return $default;
    }

    /**
     * @param  array  $array
     * @param  array  $order
     * @return array
     */
    public static function sortByOrder($array, $order)
    {
        uksort(
            $array,
            function ($key1, $key2) use ($order) {
                return (array_search($key1, $order) > array_search($key2, $order));
            }
        );

        return $array;
    }

    /**
     * @param  array  $array
     * @param  callable  $callback
     * @param  false  $descending
     * @param  int  $options
     * @return array
     */
    public static function sortByCallback($array, $callback, $descending = false, $options = SORT_REGULAR)
    {
        $results = [];

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($array as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending
            ? arsort($results, $options)
            : asort($results, $options);

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $array[$key];
        }

        return $results;
    }

    /**
     * Removes an item from an array and returns the value.
     * If the key does not exist in the array, the default value will be returned instead.
     * The key may be specified in a dot format to retrieve the value of a sub-array.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function extract(&$array, $key, $default = null)
    {
        $value = $default;

        // if the exact key exists in the top-level, remove it
        if (array_key_exists($key, $array)) {
            $value = $array[$key];
            unset($array[$key]);

            return $value;
        }

        $parts = explode('.', $key);

        while (count($parts) > 1) {
            $part = array_shift($parts);

            if (isset($array[$part]) && is_array($array[$part])) {
                $array = &$array[$part];
            } else {
                continue;
            }
        }

        $partsKey = array_shift($parts);

        if (isset($array[$partsKey])) {
            $value = $array[$partsKey];
            unset($array[$partsKey]);
        }

        return $value;
    }

    /**
     * @param  array  $array
     * @param  null|callable  $callback
     * @return array
     */
    public static function filter($array, $callback = null)
    {
        $res = is_callable($callback) ? array_filter($array, $callback) : array_filter($array);

        return array_map(
            function ($v) use ($callback) {
                if (is_array($v)) {
                    return self::filter($v, $callback);
                }

                return $v;
            },
            $res
        );
    }

    /**
     * @param  array  $items
     * @param  mixed  $itemKey
     * @param  mixed  $itemValue
     * @return bool
     * @throws \Exception
     */
    public static function hasItem($items, $itemKey, $itemValue)
    {
        foreach ($items as $item) {
            if (ArrayHelper::getValue($item, $itemKey) === $itemValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array  $array
     * @param  string  $key
     * @return array
     * @throws \Exception
     */
    public static function unique($array, $key = 'id')
    {
        if (empty($array)) {
            return $array;
        }

        $first = self::first($array);
        if (is_object($first) || is_array($first)) {
            $known = [];

            return array_filter(
                $array,
                function ($item) use (&$known, $key) {
                    $value = ArrayHelper::getValue($item, $key);
                    $unique = !in_array($value, $known);
                    $known[] = $value;

                    return $unique;
                }
            );
        }

        return array_unique($array);
    }

    /**
     * @param  array  $array
     * @param  array  $keys
     * @param  bool  $saveKeys
     * @return array
     */
    public static function getValues($array, $keys, $saveKeys = false)
    {
        if ($saveKeys) {
            return array_intersect_key(
                $array,
                array_flip($keys)
            );
        }

        return array_map(
            function ($x) use ($array) {
                return $array[$x];
            },
            $keys
        );
    }

    /**
     * @param $array
     * @param  callable|string|null  $value
     * @return false|float|int
     */
    public static function sum($array, $value = null)
    {
        if ($value === null) {
            return array_sum($array);
        }

        if (is_string($value)) {
            return array_sum(ArrayHelper::getColumn($array, $value));
        }

        if (is_callable($value)) {
            return array_reduce(
                $array,
                function ($sum, $item) use ($value) {
                    return $sum + call_user_func($value, $item);
                }
            );
        }

        return false;
    }

    /**
     * @param $array
     * @param  string  $from
     * @param  string  $to
     * @return array
     */
    public static function mapList($array, $from = 'k', $to = 'v')
    {
        return ArrayHelper::map($array, $from, $to);
    }

    /**
     * @param  string  $glue
     * @param  array  $array
     * @param  string  $column
     * @param  bool  $filter
     * @return string
     */
    public static function implodeColumn($glue, $array, $column, $filter = true)
    {
        $pieces = ArrayHelper::getColumn($array, $column);

        if ($filter) {
            $pieces = array_filter($pieces);
        }

        return implode($glue, $pieces);
    }
}