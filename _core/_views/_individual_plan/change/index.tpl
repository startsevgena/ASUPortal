{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Изменения в годовом индивидуальном плане</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("title", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить изменение в годовом индивидуальном плане')) { location.href='changes.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="changes.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->title}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "changes.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/change/index.right.tpl"}
{/block}