<?php

namespace common\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

/**
 * Description of Serializer
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Serializer
{
    public static $properties = [];

    /**
     * Serializes a model object.
     * @param mixed $object
     * @return array the array representation of the model
     */
    public static function serializeObject($object, $expands = [], $excepts = [], $properties = [])
    {
        if (is_object($object)) {
            $class = get_class($object);
            if (!empty($properties)) {
                $oldProperties = static::$properties;
                static::$properties = array_merge(static::$properties,$properties);
            }
            if (isset(static::$properties[$class])) {
                $data = static::serializeWithProperties($object, static::$properties[$class]);
                if (is_scalar($data)) {
                    return $data;
                } elseif (is_object($data)) {
                    return static::serializeObject($data, $expands, $excepts);
                }
            } elseif ($object instanceof Model) {
                $data = $object->attributes;
            } else {
                $data = [];
                foreach ($object as $key => $value) {
                    $data[$key] = $value;
                }
            }
            if (!empty($expands)) {
                $_expands = static::resolveExpand($expands);
                foreach (array_keys($_expands) as $field) {
                    if (!array_key_exists($field, $data)) {
                        $data[$field] = $object->$field;
                    }
                }
            }
        } else {
            $data = $object;
        }
        if (!empty($excepts)) {
            $_excepts = static::resolveExpand($excepts);
            foreach ($_excepts as $field => $child) {
                if (empty($child) && $field != '*') {
                    unset($data[$field]);
                } elseif ($field == '*') {
                    foreach ($child as $field) {
                        unset($data[$field]);
                    }
                }
            }
        }

        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                if (is_int($key)) {
                    $itemExpands = $expands;
                    $itemExcepts = $excepts;
                } else {
                    $itemExpands = isset($_expands) && isset($_expands[$key]) ? $_expands[$key] : [];
                    $itemExcepts = isset($_excepts) && isset($_excepts[$key]) ? $_excepts[$key] : [];
                    if (isset($_excepts['*'])) {
                        foreach ($_excepts['*'] as $field) {
                            $itemExcepts['*'][] = $field;
                        }
                    }
                }
                $data[$key] = static::serializeObject($value, $itemExpands, $itemExcepts);
            }
        }
        if (isset($oldProperties)) {
            static::$properties = $oldProperties;
        }
        return $data;
    }

    protected static function serializeWithProperties($object, $properties)
    {
        $formatter = Yii::$app->getFormatter();
        if (is_callable($properties)) {
            return call_user_func($properties, $object);
        }
        if (is_string($properties)) {
            if (strpos($properties, ':') !== false) {
                list($properties, $format) = explode(':', $properties, 2);
                $value = ArrayHelper::getValue($object, $properties);
                return $formatter->format($value, $format);
            }
            return ArrayHelper::getValue($object, $properties);
        }
        $result = [];
        foreach ($properties as $key => $field) {
            $format = null;
            if (is_string($field) && strpos($field, ':') !== false) {
                list($field, $format) = explode(':', $field, 2);
            }
            $value = ArrayHelper::getValue($object, $field);
            if ($format) {
                $value = $formatter->format($value, $format);
            }
            $result[is_int($key) ? $field : $key] = $value;
        }
        return $result;
    }

    /**
     *
     * @param array $expands
     * @return array Description
     */
    protected static function resolveExpand(array $expands, $olds = [])
    {
        $olds = [];
        foreach ($expands as $field) {
            $fields = explode('.', $field, 2);
            $olds[$fields[0]][] = isset($fields[1]) ? $fields[1] : false;
        }
        return array_map('array_filter', $olds);
    }
}

Serializer::$properties = require 'properties.php';