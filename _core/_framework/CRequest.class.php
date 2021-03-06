<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 9:49
 * To change this template use File | Settings | File Templates.
 *
 * Класс для работы со строкой запроса
 */
class CRequest {
    /**
     * Берет переменную типа int из строки запроса
     *
     * @static
     * @param $key
     * @return int
     */
    public static function getInt($key, $model = null) {
        if (array_key_exists($key, $_GET)) {
           return (int) $_GET[$key];
        } elseif (array_key_exists($key, $_POST)) {
            return (int) $_POST[$key];
        } else {
            return 0;
        }
    }
    /**
     * Берет переменную строкового типа из строки запроса
     *
     * @static
     * @param $key
     * @return string
     */
    public static function getString($key, $model = null) {
        if (!is_null($model)) {
            if (array_key_exists($model, $_GET)) {
                $get = $_GET[$model];
                if (array_key_exists($key, $get)) {
                    return $get[$key];
                } else {
                    return "";
                }
            } elseif (array_key_exists($model, $_POST)) {
                $post = $_POST[$model];
                if (array_key_exists($key, $post)) {
                    return $post[$key];
                } else {
                    return "";
                }
            } else {
                return "";
            }
        } else {
            if (array_key_exists($key, $_GET)) {
                return (string) $_GET[$key];
            } elseif (array_key_exists($key, $_POST)) {
                return (string) $_POST[$key];
            } else {
                return "";
            }
        }
    }
    /**
     * Массив из запроса
     *
     * @static
     * @param $key
     * @return array
     */
    public static function getArray($key) {
        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        } elseif (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        } else {
            return array();
        }
    }

    /**
     * Получить значение фильтра
     *
     * @param $name
     * @return null|int
     */
    public static function getFilter($name) {
        $filters = new CArrayList();
        foreach (explode("_", CRequest::getString("filter")) as $filter) {
            $values = explode(":", $filter);
            if (count($values) > 1) {
                if ($values[1] != 0) {
                    $filters->add($values[0], $values[1]);
                }
            }
        }
        return $filters->getItem($name);
    }
}
