{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Пользователи</h2>

    {CHtml::helpForCurrentPage()}

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("fio", $users->getFirstItem())}</th>
            <th>{CHtml::tableOrder("login", $users->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kadri_id", $users->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $users->getItems() as $user}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить пользователя {$user->getName()}')) { location.href='?action=delete&id={$user->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$user->getId()}">{$user->getName()}</a></td>
            <td>{$user->getLogin()}</td>
            <td>
                {if !is_null($user->getPerson())}
                    {$user->getPerson()->getName()}
                {/if}
            </td>
            <td>{$user->comment}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_users/users/index.right.tpl"}
{/block}