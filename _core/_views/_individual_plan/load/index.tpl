{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальные планы преподавателей</h2>

    {CHtml::helpForCurrentPage()}

    {if $persons->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
                <th></th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $persons->getItems() as $person}
            <tr>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">{counter}</td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">{$person->fio}</td>
                {if $person->getIndPlansByYears()->getCount() == 0}
                    <td>Добавить что-нибудь</td>
                {/if}
            </tr>
                {foreach $person->getIndPlansByYears()->getItems() as $load}
                    <tr>
                        <td>
                            {if !is_null($load->year)}
                                {$load->year->getValue()}
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/index.right.tpl"}
{/block}