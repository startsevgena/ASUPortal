{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Дипломные темы студентов</h2>

    {CHtml::helpForCurrentPage()}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("diplom_confirm", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("dipl_name", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("pract_place", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kadri_id", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("student_id", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("group_id", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("diplom_preview", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("date_act", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("foreign_lang", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("protocol_2aspir_id", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("recenz_id", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("study_mark", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("gak_num", $diploms->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $diploms->getFirstItem())}</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $diploms->getItems() as $diplom}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить диплом {$diplom->dipl_name}')) { location.href='?action=delete&id={$diplom->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td>
                {if !is_null($diplom->confirmation)}
                    {$diplom->confirmation->getValue()}
                {/if}
            </td>
            <td><a href="?action=edit&id={$diplom->getId()}">{$diplom->dipl_name}</a></td>
            <td>
                {if is_null($diplom->practPlace)}
                    {$diplom->pract_place}
                {else}
                    {$diplom->practPlace->getValue()}
                {/if}
            </td>
            <td>
                {if !is_null($diplom->person)}
                    {$diplom->person->getName()}
                {/if}
            </td>
            <td>
                {if !is_null($diplom->student)}
                    <a href="{$web_root}_modules/_students/?action=edit&id={$diplom->student->getId()}">{$diplom->student->getName()}</a>
                {/if}
            </td>
            <td>
                {if !is_null($diplom->student)}
                    {if !is_null($diplom->student->getGroup())}
                        {$diplom->student->getGroup()->getName()}
                    {/if}
                {/if}
            </td>
            <td>
                {if $diplom->getLastPreviewDate() != "0"}
                    {$diplom->getLastPreviewDate()|date_format}
                {/if}
            </td>
            <td>
                {$diplom->date_act|date_format:"d.m.Y"}
            </td>
            <td>
                {if !is_null($diplom->language)}
                    {$diplom->language->getValue()}
                {/if}
            </td>
            <td>
                {if !is_null($diplom->recomendationProtocol)}
                    {$diplom->recomendationProtocol->getNumber()} от {$diplom->recomendationProtocol->getDate()}
                {/if}
            </td>
            <td>
                {if !is_null($diplom->reviewer)}
                    {$diplom->reviewer->getName()}
                {/if}
            </td>
            <td>
                {if !is_null($diplom->mark)}
                    {$diplom->mark->getValue()}
                {/if}
            </td>
            <td>
                {$diplom->gak_num}
            </td>
            <td>
                {$diplom->comment}
            </td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_diploms/index.right.tpl"}
{/block}