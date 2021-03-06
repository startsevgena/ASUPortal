<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:35
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelField extends CActiveModel{
    protected $_table = TABLE_CORE_MODEL_FIELDS;
    protected $_translations = null;
    protected $_validators = null;
    protected $_model = null;

    public $model_id;

    public function relations() {
        return array(
            "translations" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_translations",
                "storageTable" => TABLE_CORE_MODEL_FIELD_TRANSLATIONS,
                "storageCondition" => "field_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelFieldTranslation"
            ),
            "model" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_model",
                "storageField" => "model_id",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModel"
            ),
            "validators" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_validators",
                "storageTable" => TABLE_CORE_MODEL_FIELD_VALIDATORS,
                "storageCondition" => "field_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelFieldValidator"
            )
        );
    }

    /**
     * Получить значение перевода для указанного языка
     *
     * @param $id
     * @return string
     */
    public function getTranslationByLangId($id) {
        $value = "";
        foreach ($this->translations->getItems() as $t) {
            if ($t->language_id = $id) {
                $value = $t->value;
            }
        }
        return $value;
    }
}