{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление таблицы доступа</h2>

    {CHtml::helpForCurrentPage()}

{include file="_acl_manager/tables/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_acl_manager/tables/add.right.tpl"}
{/block}