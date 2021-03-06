<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.02.13
 * Time: 18:38
 * To change this template use File | Settings | File Templates.
 */
class CStudentGroupsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Учебные группы студентов");
        $this->_useDojo = true;

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("st_group.*")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->order("st_group.id desc");
        $set->setQuery($query);
        /**
         * Фильтры пока никакие не делаю, так как некогда
         */
        $selectedGroup = null;
        if (!is_null(CRequest::getFilter("group"))) {
            $query->condition("st_group.id = ".CRequest::getFilter("group"));
            $selectedGroup = CStaffManager::getStudentGroup(CRequest::getFilter("group"));
        }
        /**
         * Финишная выборка
         */
        $groups = new CArrayList();
        foreach($set->getPaginated()->getItems() as $item) {
            $group = new CStudentGroup($item);
            $groups->add($group->getId(), $group);
        }
        /**
         * Подключаем скрипты
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        /**
         * Передаем значения в представление
         */
        $this->setData("selectedGroup", $selectedGroup);
        $this->setData("groups", $groups);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_student_groups/index.tpl");
    }
    public function actionEdit() {
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $students = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("group", $group);
        $this->setData("students", $students);
        $this->renderView("_student_groups/edit.tpl");
    }
    public function actionAdd() {
        $group = new CStudentGroup();
        $this->setData("group", $group);
        $this->renderView("_student_groups/add.tpl");
    }
    public function actionSave() {
        $group = new CStudentGroup();
        $group->setAttributes(CRequest::getArray($group::getClassName()));
        if ($group->validate()) {
            $group->save();
            $this->redirect("?action=index");
            return true;
        }
        $students = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("group", $group);
        $this->setData("students", $students);
        $this->renderView("_student_groups/edit.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("term");
        /**
         * Ищем группу по названию
         */
        $query = new CQuery();
        $query->select("st_group.id, st_group.name")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->condition("LCASE(st_group.name) like '%".mb_strtolower($term)."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "type" => "1",
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
            );
        }
        echo json_encode($res);
    }

    /**
     * Получаем список студентов JSON-ом
     */
    public function actionJSONGetStudents() {
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $arr = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $arr[$student->getId()] = $student->getName();
        }
        echo json_encode($arr);
    }
    /**
     * Все студенты без оценок по учебному плану в указанной группе
     */
    public function actionGetStudentsWithoutMarks() {
        $result = array();
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $corriculum = $group->corriculum;
        if (!is_null($corriculum)) {
            /**
             * Набираем список дисциплин, которые нужно проверить
             * Дисциплины с дочками не берем
             */
            $disciplines = array();
            foreach ($corriculum->cycles->getItems() as $cycle) {
                foreach ($cycle->disciplines->getItems() as $disc) {
                    if ($disc->children->getCount() == 0) {
                        if (!is_null($disc->discipline)) {
                            $disciplines[$disc->discipline_id] = $disc->discipline->getValue();
                        }
                    } else {
                        foreach ($disc->children->getItems() as $child) {
                            if (!is_null($child->discipline)) {
                                $disciplines[$child->discipline_id] = $child->discipline->getValue();
                            }
                        }
                    }
                }
            }
            /**
             * А так же практики
             */
            foreach ($corriculum->practices->getItems() as $practice) {
                if (!is_null($practice->discipline)) {
                    $disciplines[$practice->discipline_id] = $practice->discipline->getValue();
                }
            }
            /**
             * Проверяем, по каким дисциплинам у студентов нет оценок
             */
            foreach ($disciplines as $d_id => $d_name) {
                foreach ($group->getStudents()->getItems() as $student) {
                    $query = new CQuery();
                    $query->select("st_act.*")
                        ->from(TABLE_STUDENTS_ACTIVITY." as st_act")
                        ->condition("st_act.student_id = ".$student->getId()." AND subject_id = ".$d_id." AND kadri_id = 380");
                    if ($query->execute()->getCount() == 0) {
                        $disc_array = array();
                        if (array_key_exists($d_name, $result)) {
                            $disc_array = $result[$d_name];
                        }
                        $disc_array[] = $student->getName();
                        $result[$d_name] = $disc_array;
                    }
                }
            }
        }
        /**
         * Сортируем по уменьшению количества студентов
         */
        uasort($result, "CStudentGroupsController::sortByItemsCount");
        $this->setData("result", $result);
        $this->renderView("_student_groups/subform.studentWithoutMarks.tpl");
    }
    public static function sortByItemsCount(array $el1, array $el2) {
        if (count($el1) == count($el2)) {
            return 0;
        }
        return (count($el1) > count($el2)) ? -1 : 1;
    }
}
