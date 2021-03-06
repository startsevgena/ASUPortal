<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 10:38
 * To change this template use File | Settings | File Templates.
 *
 * Вывод html-блоков нормального вида. Унификация рулит!
 */
class CHtml {
    private static $_calendarInit = false;
    private static $_multiselectInit = false;
    private static $_printFormViewInit = false;
    private static function getFielsizeClass() {
        $result = "span5";
        if (!is_null(CSession::getCurrentUser())) {
            if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
                if (CSession::getCurrentUser()->getPersonalSettings()->portal_input_size != "") {
                    $result = CSession::getCurrentUser()->getPersonalSettings()->portal_input_size;
                }
            }
        }
        return $result;
    }

    public static function button($value, $onClick = "") {
        echo '<input type="button" value="'.$value.'" onclick="'.$onClick.'">';
    }
    /**
     * Вывод элемента div
     *
     * @static
     * @param $id
     * @param string $content
     * @param string $class
     * @param string $html
     */
    public static function div($id, $content = "", $class = "", $html = "") {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<div'.$inline.">".$content."</div>";
    }
    /**
     * Вывод выпадающего списка
     * $values - простой array вида [ключ]=>значение для подстановки
     * $selected - выбранный элемент
     *
     * @static
     * @param $name
     * @param $values
     * @param null $selected
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function dropDownList($name, $values, $selected = null, $id = "", $class = "", $html = "") {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        } else {
            $inline .= ' class="'.self::getFielsizeClass().'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        /**
         * Проверим, какого вида ключи у подставляемых значений.
         * Если числовые, то проверяем наличие нуля, если ключи нечисловые, то
         * не добавляем лишнего выбиратора
         */
        $numeric = true;
        foreach ($values as $key=>$value) {
            if (!is_numeric($key)) {
                $numeric = false;
            }
        }
        if ($numeric) {
            if (!array_key_exists(0, $values)) {
                $values[0] = "- Выберите из списка (".count($values).") -";
            }
        }
        echo '<select name="'.$name.'" '.$inline.'>';
        // часто выбор делается из словаря, так что преобразуем объекты CTerm к строке
        foreach ($values as $key=>$value) {
            if (is_object($value)) {
                if (strtoupper(get_class($value)) == "CTERM") {
                    $values[$key] = $value->getValue();
                }
            }
        }
        foreach ($values as $key=>$value) {
            $checked = "";
            if (is_null($selected)) {
                if ($key == 0) {
                    $checked = 'selected="selected"';
                }
            } elseif ($selected != "") {
                if ($key == $selected) {
                    $checked = 'selected="selected"';
                }
            }
            echo '<option '.$checked.' value="'.$key.'">'.$value.'</option>';
        }
        echo '</select>';
    }
    /**
     * @static
     * @param $name
     * @param CModel $model
     * @param $values
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function activeDropDownList($name, CModel $model, $values, $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        self::dropDownList($field, $values, $model->$name, $id, $class, $html);
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    /**
     * Вывод ссылки
     *
     * @static
     * @param $text
     * @param $anchor
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function link($text, $anchor, $id = "", $class = "", $html = "") {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<a href="'.$anchor.'" '.$inline.'>'.$text.'</a>';
    }
    /**
     * Скрытое поле
     *
     * @static
     * @param $name
     * @param $value
     */
    public static function hiddenField($name, $value, $id = "") {
        echo '<input type="hidden" name="'.$name.'" ';
        if ($id != "") {
            echo 'id="'.$id.'" ';
        }
        echo 'value="'.$value.'">';
    }
    /**
     * Скрытое поле с автозаполнением значения из модели
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     */
    public static function activeHiddenField($name, CModel $model, $multiple_key = "", $value = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        if ($value == "") {
            $value = $model->$name;
        }
        self::hiddenField($field, $value);
    }
    /**
     * Однострочное текстовое поле
     *
     * @static
     * @param $name
     * @param $value
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function textField($name, $value = null, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class == "") {
            $class = self::getFielsizeClass();
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input type="text" name="'.$name.'" value="'.htmlspecialchars($value).'" '.$inline.'>';
    }
    /**
     * Активное текстовое поле
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function activeTextField($name, CModel $model, $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        self::textField($field, $model->$name, $id, $class, $html);
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    public static function activeDateField($name, CModel $model, $format = "dd.mm.yyyy", $id = "", $class = "", $html = "") {
        $field = $model::getClassName()."[".$name."]";
        if ($id == "") {
            $id = $field;
        }
        if ($format == "") {
            $format = "%d.%m.%Y";
        }
        $id = str_replace("[", "_", $id);
        $id = str_replace("]", "_", $id);

        ?>
        <div class="input-append date <?php echo self::getFielsizeClass(); ?> datepicker" id="<?php echo $id; ?>" data-date="<?php echo $model->$name; ?>" data-date-format="<?php echo $format; ?>">
            <input name="<?php echo $field; ?>" class="<?php echo self::getFielsizeClass(); ?>" type="text" value="<?php echo $model->$name; ?>">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <?php
        if (!self::$_calendarInit) {
            self::$_calendarInit = true;
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery(".datepicker").datepicker();
                });
            </script>
            <?php
        }
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        if ($fieldRequired) {
            self::requiredStar();
        }

        /*
        self::textField($field, $model->$name, $id, $class, $html);
        if (!self::$_calendarInit) {
            self::$_calendarInit = true;
            echo '
            <script type="text/javascript" src="'.WEB_ROOT.'scripts/calendar.js"></script>
            <script type="text/javascript" src="'.WEB_ROOT.'scripts/calendar-setup.js"></script>
            <script type="text/javascript" src="'.WEB_ROOT.'scripts/lang/calendar-ru_win_.js"></script>
            <link rel="stylesheet" type="text/css" media="all" href="'.WEB_ROOT.'css/calendar-win2k-asu.css" title="win2k-cold-1" />';
        }
        echo '
        <button type="reset" id="'.$id.'_select">...</button>
            <script type="text/javascript">
                Calendar.setup({
                    inputField     :    "'.$id.'",      // id of the input field
                    ifFormat       :    "'.$format.'",       // format of the input field "%m/%d/%Y %I:%M %p"
                    showsTime      :    false,            // will display a time selector
                    button         :    "'.$id.'_select",   // trigger for the calendar (button ID)
                    singleClick    :    true,           // double-click mode false
                    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                });
            </script>';
        */
    }
    public static function activeTextBox($name, CModel $model, $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        self::textBox($field, $model->$name, $id, $class, $html);
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    /**
     * Поле для ввода пароля
     *
     * @static
     * @param $name
     * @param null $value
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function passwordField($name, $value = null, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input type="password" name="'.$name.'" value="'.$value.'" '.$inline.'>';
    }
    /**
     * Метка
     *
     * @static
     * @param $text
     * @param $for
     */
    public static function label($text, $for) {
        echo '<label for="'.$for.'" class="control-label" >'.$text.'</label>';
    }
    /**
     * Метка, привязанная к модели
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     */
    public static function activeLabel($name, CModel $model) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        if (strpos($name, "[") !== false) {
            $modelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$modelName;
        }
        $labels = CCoreObjectsManager::getAttributeLabels($model);
        if (array_key_exists($name, $labels)) {
            $field = $model::getClassName()."[".$name."]";
            self::label($labels[$name], $field);
        } else {
            $field = $model::getClassName()."[".$name."]";
            self::label($name, $field);
        }
    }
    /**
     * Кнопка отправки формы
     *
     * @static
     * @param $value
     */
    public static function submit($value, $canChooseContinue = true) {
        if ($canChooseContinue) {
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery("#_saveAndContinue").click(function(){
                        var form = jQuery(this).parents("form:first");
                        jQuery("input[name=_continueEdit]").val("1");
                        jQuery(form).submit();
                        return false;
                    });
                    jQuery("#_saveAndBack").click(function(){
                        var form = jQuery(this).parents("form:first");
                        jQuery("input[name=_continueEdit]").val("0");
                        jQuery(form).submit();
                        return false;
                    });
                });
            </script>
            <input type="hidden" name="_continueEdit" value="1">
            <div class="btn-group">
                <button class="btn btn-primary"><?php echo $value; ?></button>
                <button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" id="_saveAndContinue">Сохранить и продолжить</a>
                    </li>
                    <li>
                        <a href="#" id="_saveAndBack">Сохранить и к списку</a>
                    </li>
                </ul>
            </div>
        <?php
        } else {
            echo '<input type="submit" class="btn btn-primary" label="'.$value.'" type="submit" value="'.$value.'">';
        }
    }
    /**
     * Большое поле для ввода
     *
     * @static
     * @param $name
     * @param null $value
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function textBox($name, $value = null, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class == "") {
            $class = self::getFielsizeClass();
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html == "") {
            $html = ' rows="5"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<textarea name="'.$name.'" '.$inline.'>'.$value.'</textarea>';
    }
    public static function checkBox($name, $value, $checked = false, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        if ($value != "") {
            $inline .= ' value="'.$value.'"';
        }
        if ($checked) {
            $checked = "checked";
        } else {
            $checked = "";
        }
        echo '<input type="checkbox" name="'.$name.'" '.$checked.' '.$inline.'>';
    }
    public static function activeCheckBoxGroup($name, CModel $model, $values = null) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        foreach ($values as $key=>$value) {
            $inputName = $model::getClassName();
            if ($submodelName !== "") {
                $inputName .= "[".$submodelName."]";
            }
            $inputName .= "[".$name."][]";
            echo '<label class="checkbox">';
            echo '<input type="checkbox" name="'.$inputName.'"';
            if (is_array($model->$name)) {
                if (array_key_exists($key, $model->$name)) {
                    echo ' checked';
                } elseif (in_array($key, $model->$name)) {
                    echo ' checked';
                }
            } elseif (is_object($model->$name)) {
                if (strtolower(get_class($model->$name)) == "carraylist") {
                    $list = $model->$name;
                    if ($list->hasElement($key)) {
                        echo ' checked';
                    }
                }
            } else {
                echo ("Какой-то неподдерживаемый тип данных для построение списка ".get_class($model->$name));
                exit;
            }
            echo ' value="'.$key.'">'.$value;
            echo '</label>';
        }
    }
    public static function activeRadioButtonGroup($name, CModel $model, $values = array(), $groupName = "") {
        foreach ($values as $key=>$value) {
            $inputName = $model::getClassName()."[".$name.$groupName."][]";
            echo '<input type="radio" name="'.$inputName.'"';
            if (is_array($model->$name)) {
                if (array_key_exists($key, $model->$name)) {
                    echo ' checked';
                } elseif (in_array($key, $model->$name)) {
                    echo ' checked';
                }
            } else {
                if ($model->$name == $value) {
                    echo ' checked';
                } elseif ($model->$name == $key) {
                    echo ' checked';
                }
            }
            echo ' value="'.$key.'">'.$value.'<br>';
        }
    }
    public static function activeCheckBox($name, CModel $model, $id = "", $class = "", $html = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        if ($model->$name == true) {
            $name = $model::getClassName()."[".$name."]";
            self::checkBox($name, "1", true, $id, $class, $html);
        } else {
            $name = $model::getClassName()."[".$name."]";
            self::checkBox($name, "1", false, $id, $class, $html);
        }
    }
    public static function error($name, CModel $model) {
        if ($model->getValidationErrors()->hasElement($name)) {
            echo '<span class="help-inline">'.$model->getValidationErrors()->getItem($name)."</span>";
        }
    }
    public static function activeMultiSelect($name, CModel $model, $values = array()) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."][]";
        // дописываем скрипт для мультивыбора
        if (!self::$_multiselectInit) {
            echo '
                <script>
                    jQuery(document).ready(function(){
                        jQuery(".multiselectClonable").change(function(){
                            var current = jQuery(this);
                            var span = jQuery(current).parent();
                            var parent = jQuery(span).parent();
                            // клонируем текущий элемент
                            jQuery(span).clone(true).appendTo(parent);
                            // у текущего элемента активируем удалялку и снимаем класс клонирования
                            var img = jQuery(span).find("img")[0];
                            jQuery(img).css("display", "");
                            jQuery(current).removeClass("multiselectClonable");
                            jQuery(current).unbind("change");
                        });
                    });
                </script>
                ';
            self::$_multiselectInit = true;
        }
        echo '<div style="margin-left: 200px; ">';
        foreach ($model->$name->getItems() as $f) {
            // отрисовываем поле со значением
            echo '<span>';
            self::dropDownList($field, $values, $f->getId());
            echo '&nbsp;&nbsp; <img src="'.WEB_ROOT.'images/design/mn.gif" style="cursor: pointer; " onclick="jQuery(this).parent().remove(); return false;" />';
            echo '<br /></span>';
        }
        // добавляем последний невыбранным
        echo '<span>';
        self::dropDownList($field, $values, null, "", "multiselectClonable");
        echo '&nbsp;&nbsp; <img src="'.WEB_ROOT.'images/design/mn.gif" style="cursor: pointer; display: none; " onclick="jQuery(this).parent().remove(); return false;" />';
        echo '<br /></span>';
        echo '</div>';
    }
    public static function activeSelect($name, CModel $model, $values = array(), $isMultiple = false, $size = 5, $id = "") {
        echo '<select name="'.$model::getClassName().'['.$name.'][]" size="'.$size.'" ';
        if ($isMultiple) {
            echo 'multiple';
        }
        if ($id != "") {
            echo ' id="'.$id.'"';
        }
        echo '>';
        // часто выбор делается из словаря, так что преобразуем объекты CTerm к строке
        foreach ($values as $key=>$value) {
            if (is_object($value)) {
                if (strtoupper(get_class($value)) == "CTERM") {
                    $values[$key] = $value->getValue();
                }
            }
        }
        foreach ($values as $k=>$v) {
            if (is_array($v)) {
                echo '<optgroup label="'.$k.'">';
                foreach ($v as $key=>$value) {
                    echo '<option value="'.$key.'" ';
                    if (is_array($model->$name)) {
                        if (array_key_exists($key, $model->$name)) {
                            echo 'selected';
                        }
                    } elseif (is_object($model->$name)) {
                        if ($model->$name->hasElement($key)) {
                            echo 'selected';
                        }
                    }
                    echo '>'.$value.'</option>';
                }
                echo '</optgroup>';
            } else {
                echo '<option value="'.$k.'" ';
                if (is_array($model->$name)) {
                    if (array_key_exists($k, $model->$name)) {
                        echo 'selected';
                    }
                } elseif (is_object($model->$name)) {
                    if ($model->$name->hasElement($k)) {
                        echo 'selected';
                    }
                }
                echo '>'.$v.'</option>';
            }
        }
        echo '</select>';
    }
    /**
     * Обработчик фильтра сотрудников по типу
     *
     * @static
     * @param $fieldId
     */
    public static function personTypeFilter($field, CModel $model) {
        $fieldId = $model::getClassName()."[".$field."]";
        echo '<span id="person_type_selector_button" style="cursor: pointer; " onclick="showPersonTypeSelector(); return false;"><img src="'.WEB_ROOT.'images/filter.gif">';
        echo '</span>';
        echo '<div id="person_type_selector" style="position: absolute; display: none; border: 1px solid #c0c0c0; background: #ffffff; padding: 5px; z-index: 100; margin-left: 300px; margin-top: -5px; ">';
        foreach (CTaxonomyManager::getCacheTypes()->getItems() as $type) {
            echo '<span><input type="checkbox" onclick="updatePersonListField(\''.$fieldId.'\'); return true; " value="'.$type->getId().'" checked>'.$type->getValue().'</span><br>';
        }
        echo '</div>';
    }
    public static function paginator(CPaginator $paginator, $action) {
        echo '<div class="pagination"><ul>';
        foreach ($paginator->getPagesList($action) as $page=>$link) {
        	if (CRequest::getString("order") !== "") {
        		$link = $link."&order=".CRequest::getString("order");
        	}
        	if (CRequest::getString("direction") !== "") {
        		$link = $link."&direction=".CRequest::getString("direction");
        	}
            if (CRequest::getString("filter") !== "") {
                $link = $link."&filter=".CRequest::getString("filter");
            }
            $toCheck = 1;
            if (CRequest::getInt("page") !== 0) {
                $toCheck = CRequest::getInt("page");
            }
            if ($toCheck == $page) {
                echo '<li class="active"><a href="'.$link.'">'.$page.'</a></li>';
            } else {
                echo '<li><a href="'.$link.'">'.$page.'</a></li>';
            }
        }
        echo '</ul></div>';
        echo '<span>Текущая страница: '.$paginator->getCurrentPageNumber().' </span>';
        echo '<span>Всего: '.$paginator->getPagesCount().'</span>';
    }
    public static function helpForCurrentPage() {
        if (!is_null(CHelpManager::getHelpForCurrentPage())) {
            echo '<div class="alert alert-info">';
            echo '<h4>'.CHelpManager::getHelpForCurrentPage()->title.'</h4>';
            echo CHelpManager::getHelpForCurrentPage()->content;
            if (CSession::getCurrentUser()->hasRole("help_add_inline")) {
                echo '<p>';
                echo '<a href="'.WEB_ROOT.'_modules/_help/?action=edit&id='.CHelpManager::getHelpForCurrentPage()->getId().'" target="_blank">Редактировать справку</a>';
                echo '</p>';
            }
            echo '</div>';
        } elseif (CSession::getCurrentUser()->hasRole("help_add_inline")) {
            echo '<div class="alert alert-info">';
            $uri = "";
            if (array_key_exists("REQUEST_URI", $_SERVER)) {
                $uri = $_SERVER["REQUEST_URI"];
                $uri = str_replace(ROOT_FOLDER, "", $uri);
            }
            echo '<a href="'.WEB_ROOT.'_modules/_help/?action=add&page='.$uri.'" target="_blank">Добавить справку для текущей страницы</a>';
            echo '</div>';
        }
    }
    public static function errorSummary(CModel $model) {
        if ($model->getValidationErrors()->getCount() > 0) {
            echo '<div class="alert alert-error">';
            foreach ($model->getValidationErrors()->getItems() as $error) {
                echo "<p>".$error."</p>";
            }
            echo '</div>';
        }
    }
    public static function activeUpload($name, CModel $model) {
        $field = $model::getClassName()."[".$name."]";
        echo '<input type="file" name="'.$field.'">';
    }
    /**
     * Печать по шаблону
     * @param $template
     */
    public static function printOnTemplate($template) {
    	$formset = CPrintManager::getFormset($template);
    	if (!is_null($formset)) {
    		$forms = $formset->activeForms;
    		$variables = $formset->computeTemplateVariables();
    		echo '<ul>';
    		foreach ($forms->getItems() as $form) {
                if ($variables["id"] != "selectedInView") {
                    $url = "?action=print".
                        "&manager=".$variables['manager'].
                        "&method=".$variables['method'].
                        "&id=".$variables['id'].
                        "&template=".$form->getId();
                    echo '<li><a href="'.WEB_ROOT.'_modules/_print/'.$url.'" target="_blank">'.$form->title.'</a></li>';
                } else {
                    if (!self::$_printFormViewInit) {
                        self::$_printFormViewInit = true;
                        ?>
                        <script>
                            function printTemplateFromView(baseUrl, formId){
                                var selected = jQuery("input[name='selectedDoc']:checked")
                                if (selected.length == 0) {
                                    alert("Выберите один или несколько документов");
                                    return false;
                                }
                                var ids = new Array();
                                for(var i = 0; i < selected.length; i++) {
                                    ids[ids.length] = selected[i].value;
                                }
                                window.open(web_root + "_modules/_print/" + baseUrl + "&id=" + ids.join(":"), "Печать по шаблону");
                            }
                        </script>
                        <?php
                    }
                    $url = "?action=print".
                        "&manager=".$variables['manager'].
                        "&method=".$variables['method'].
                        "&template=".$form->getId();
                    echo '<li><a href="#" onclick="printTemplateFromView(\''.$url.'\', '.$form->getId().'); return false;" target="_blank">'.$form->title.'</a></li>';;
                }
    		}
    		echo '</ul>';
    	}
    }

    /**
     * Подготоваливает к вывод данные для печати группы
     * записей по указанному шаблону
     *
     * @param $template
     */
    public static function printGroupOnTemplate($template) {
        $formset = CPrintManager::getFormset($template);
        if (!is_null($formset)) {
            $forms = $formset->activeForms;
            $variables = $formset->computeTemplateVariables();
            echo "<ul>";
            foreach ($forms->getItems() as $form) {
                echo '<li><a href="#" onclick="printWithTemplate(';
                echo "'".$variables['manager']."'";
                echo ", '".$variables['method']."'";
                echo ", '".$form->getId()."'";
                echo '); return false;">'.$form->title.'</a></li>';
            }
            echo "</ul>";
        }
    }
    public static function tableOrder($field, CModel $model = null) {
        if (is_null($model)) {
            return "";
        }
        $labels = CCoreObjectsManager::getAttributeLabels($model);
        if (array_key_exists($field, $labels)) {
            $label = $labels[$field];
        } else {
            $label = $field;
        }
    	if (CRequest::getString("action") !== "") {
    		$actions[] = "action=".CRequest::getString("action");
    	}
    	if (CRequest::getInt("page") !== 0) {
    		$actions[] = "page=".CRequest::getInt("page");
    	}
        if (CRequest::getString("filter") !== "") {
            $actions[] = "filter=".CRequest::getString("filter");
        }
    	$actions[] = "order=".$field;
    	if (CRequest::getString("order") == $field) {
    		if (CRequest::getString("direction") == "") {
    			$actions[] = "direction=asc";
    		} elseif (CRequest::getString("direction") == "asc") {
    			$actions[] = "direction=desc";
    		} elseif (CRequest::getString("direction") == "desc") {
    			$actions[] = "direction=asc";
    		}
    		$label = '<a href="?'.implode($actions, "&").'">'.$label.'</a>';
    	} else {
    		$actions[] = "direction=desc";
    		$label = '<a href="?'.implode($actions, "&").'">'.$label.'</a>';
    	}
    	echo $label;
    }
    public static function activeNamesSelect($field, CModel $model) {
        echo '
        <table border="0" cellpadding="2" cellspacing="0" class="tableBlank" style="width: 300px; ">
            <tr>
                <td><input type="text" name="'.$field.'" id="'.$field.'" style="width: 97%; "></td>
                <td style="width: 16px; "><img src="'.WEB_ROOT.'images/tango/22x22/actions/edit-find.png" id="'.$field.'_selector" style="height: 19px; "></td>
            </tr>
            <tr>
                <td valign="top">
                    <select id="'.$field.'_select" style="width: 100%; border: none; " size="5">';
        foreach ($model->$field->getItems() as $entry) {
            echo '<option value="'.$entry->getId().'" type="'.$entry->getType().'">'.$entry->getName().'</option>';
        }
        echo '      </select>
                </td>
                <td valign="top"><img src="'.WEB_ROOT.'images/tango/22x22/actions/edit-clear.png" id="'.$field.'_deleter" style="height: 19px; "></td>
            </tr>
        </table>';
        foreach ($model->$field->getItems() as $entry) {
            echo '
            <input type="hidden" name="'.$field.'[id][]" value="'.$entry->getId().'">
            <input type="hidden" name="'.$field.'[name][]" value="'.$entry->getName().'">
            <input type="hidden" name="'.$field.'[type][]" value="'.$entry->getType().'">
            ';
        }
    }

    /**
     * Звездочка для отметки обязательности поля
     */
    private static function requiredStar() {
        echo '<span class="field_required">*</span>';
    }
}
