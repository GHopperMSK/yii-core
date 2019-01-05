<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\base;


/**
 * ConfigurableObject child classes constructor should be done like the following:
 *
 * ```php
 * public function __construct($param1, $param2, ..., iterable $config = [])
 * {
 *     ...
 *     parent::__construct($config);
 * }
 * ```
 *
 * That is, a `$config` parameter (defaults to `[]`) should be declared as the last parameter
 * of the constructor, and the parent implementation should be called at the end of the constructor.
 */
class BaseObject
{
    public function __construct(iterable $config = [])
    {
        if (!empty($config)) {           
            static::configure($this, $config);
        }
    }

    /**
     * Configures an object with the given configuration.
     * @param object $object the object to be configured
     * @param iterable $config property values and methods to call
     * @return object the object itself
     */
    public static function configure(object $object, iterable $config): object
    {
        foreach ($config as $action => $arguments) {
            if (substr($action, -2) === '()') {
                // method call
                \call_user_func_array([$object, substr($action, 0, -2)], $arguments);
            } else {
                // property
                $object->$action = $arguments;
            }
        }

        return $object;
    }
}
