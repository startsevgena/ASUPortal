<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplinesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionEdit() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Подключаем скрипты для няшности
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("cycle", $discipline->cycle);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/edit.tpl");
    }
    public function actionAdd() {
        $discipline = new CCorriculumDiscipline();
        $discipline->cycle_id = CRequest::getInt("id");
        $this->setData("cycle", CCorriculumsManager::getCycle(CRequest::getInt("id")));
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionSave() {
        $discipline = new CCorriculumDiscipline();
        $discipline->setAttributes(CRequest::getArray($discipline::getClassName()));
        if ($discipline->validate()) {
            $discipline->save();
            $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
            return true;
        }
        $this->setData("cycle", $discipline->cycle);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionDel() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $id = $discipline->cycle_id;
        $discipline->remove();
        $this->redirect("cycles.php?action=edit&id=".$id);
    }
    public function actionUp() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Проверим, вдруг это первый запуск и ничего не отсортировано еще
         */
        if (is_null($discipline->ordering)) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $i = 1;
                foreach ($cycle->disciplines->getItems() as $d) {
                    $d->ordering = $i;
                    $d->save();
                    $i++;
                }
            }
        }
        /**
         * Двигаем только если текущая не первая
         */
        if ($discipline->ordering > 1) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $d = $cycle->getNthDiscipline(($discipline->ordering - 1));
                if (!is_null($d)) {
                    $curr = $discipline->ordering;
                    $d->ordering = $curr;
                    $discipline->ordering = ($curr - 1);
                    $discipline->save();
                    $d->save();
                }
            }
        }
        /**
         * Возвращаем обратно
         */
        $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
    }
    public function actionDown() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Проверим, вдруг это первый запуск и ничего не отсортировано еще
         */
        if (is_null($discipline->ordering)) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $i = 1;
                foreach ($cycle->disciplines->getItems() as $d) {
                    $d->ordering = $i;
                    $d->save();
                    $i++;
                }
            }
        }
        /**
         * Двигаем только если текущая не последняя
         */
        $cycle = $discipline->cycle;
        if (!is_null($cycle)) {
            if ($discipline->ordering < $cycle->disciplines->getCount()) {
                $curr = $discipline->ordering;
                $d = $cycle->getNthDiscipline($curr + 1);
                if (!is_null($d)) {
                    $d->ordering = $curr;
                    $discipline->ordering = ($curr + 1);
                    $discipline->save();
                    $d->save();
                }
            }
        }
        /**
         * Возвращаем обратно
         */
        $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
    }
}
