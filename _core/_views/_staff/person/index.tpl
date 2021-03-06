{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Сотрудники кафедры</h2>

    {CHtml::helpForCurrentPage()}

    <script>
        function getFilters() {
            filters = new Object();
            var items = jQuery("#filters p");
            jQuery.each(items, function(key, value){
                // получаем название фильтра, он хранится в label-е
                var label = jQuery(value).find("label");
                var filter_name = "";
                if (label.length > 0) {
                    filter_name = jQuery(label[0]).attr("for");
                }
                var filter_value = "";
                var val = jQuery(value).find("select");
                if (val.length > 0) {
                    filter_value = jQuery(val[0]).val();
                } else {
                    val = jQuery(value).find("input");
                    if (val.length > 0) {
                        filter_value = jQuery(val[0]).val();
                    }
                }
                /**
                 * Если есть что добавлять, то добавляем
                 */
                if (filter_name !== "") {
                    filters[filter_name] = filter_value;
                }
            });
            return filters;
        }
        /**
         * Очистка указанного фильтра
         * @param type
         */
        function removeFilter(type) {
            var filters = getFilters();
            var action = "index.php?action=index&filter=";
            var actions = new Array();
            jQuery.each(filters, function(key, value){
                if (key !== type) {
                    actions[actions.length] = key + ":" + value;
                }
            });
            action = action + actions.join("_");
            window.location.href = action;
        }
        function addFilter(key, value) {
            var filters = getFilters();
            var action = "index.php?action=index&filter=";
            var actions = new Array();
            filters[key] = value;
            jQuery.each(filters, function(filter_key, filter_value){
                actions[actions.length] = filter_key + ":" + filter_value;
            });
            action = action + actions.join("_");
            window.location.href = action;
        }
        jQuery(document).ready(function(){
            /**
             * Добавляем автопоиск
             */
            jQuery("#search").autocomplete({
                source: web_root + "_modules/_staff/index.php?action=search",
                minLength: 2,
                select: function(event, ui) {
                    window.location.href= "?action=index&filter=" + ui.item.filter + ":" + ui.item.object_id;
                }
            });
            /**
             * Для всех опубликованных фильтров добавляем
             * автоматический переключатель
             */
            var items = jQuery("#filters p");
            jQuery.each(items, function(key, value){
                var input = jQuery(value).find("select");
                if (input.length > 0) {
                    input = input[0];
                    jQuery(input).change(function(){
                        addFilter(jQuery(this).attr("id"), jQuery(this).val());
                    });
                }
            });
        });
    </script>

    <table border="0" width="100%" class="tableBlank">
        <tr>
            <td valign="top">
                <form id="filters">
                    <p>
                        <label for="type">Тип участия на кафедре</label>
                        {CHtml::dropDownList("types", $types, $selectedType, "type")}
                        {if !is_null($selectedType)}
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('type'); return false; "/></span>
                        {/if}
                    </p>
                    {if !is_null($selectedGender)}
                        <p>
                            <label for="gender">Пол</label>
                            {CHtml::dropDownList("genders", $genders, $selectedGender, "gender")}
                            {if !is_null($selectedGender)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('gender'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedRole)}
                        <p>
                            <label for="role">Роль на кафедре</label>
                            {CHtml::dropDownList("roles", $roles, $selectedRole, "role")}
                            {if !is_null($selectedRole)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('role'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedFamily)}
                        <p>
                            <label for="family">Семейное положение</label>
                            {CHtml::dropDownList("familyStatuses", $familyStatuses, $selectedFamily, "family")}
                            {if !is_null($selectedFamily)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('family'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedLanguage)}
                        <p>
                            <label for="language">Иностранный язык</label>
                            {CHtml::dropDownList("languages", $languages, $selectedLanguage, "language")}
                            {if !is_null($selectedLanguage)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('language'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedPost)}
                        <p>
                            <label for="post">Должность</label>
                            {CHtml::dropDownList("post", $posts, $selectedPost, "post")}
                            {if !is_null($selectedPost)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('post'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedTitle)}
                        <p>
                            <label for="title">Звание</label>
                            {CHtml::dropDownList("title", $titles, $selectedTitle, "title")}
                            {if !is_null($selectedTitle)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('title'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedDegree)}
                        <p>
                            <label for="degree">Ученая степень</label>
                            {CHtml::dropDownList("degree", $degrees, $selectedDegree, "degree")}
                            {if !is_null($selectedDegree)}
                                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('degree'); return false; "/></span>
                            {/if}
                        </p>
                    {/if}
                    {if !is_null($selectedPerson)}
                        <p>
                            <label for="person">Сотрудник</label>
                            <input type="hidden" name="person" value="{$selectedPerson->getId()}">
                            {$selectedPerson->getName()}
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('person'); return false; "/></span>
                        </p>
                    {/if}
                </form>
            </td>
            <td valign="top" width="200px">
                <p>
                    <input type="text" id="search" style="width: 100%; " placeholder="Поиск">
                </p>
            </td>
        </tr>
    </table>

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
            <th>{CHtml::tableOrder("types", $persons->getFirstItem())}</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $persons->getItems() as $person}
            <tr>
                <td><a href="#" onclick="if (confirm('Действительно удалить сотрудника {$person->getName()}')) { location.href='?action=delete&id={$person->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td>{counter}</td>
                <td><a href="?action=edit&id={$person->getId()}">{$person->getName()}</a></td>
                <td>
                    {$needSeparation = false}
                    {foreach $person->getTypes()->getItems() as $type}
                        {if $needSeparation}
                            ,
                        {/if}
                        {$type->getValue()}
                        {$needSeparation = true}
                    {/foreach}
                </td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_staff/person/index.right.tpl"}
{/block}