<script>
    jQuery(document).ready(function(){
        jQuery("#date_act").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
    {if !is_null($diplom->getId())}
    jQuery.ajax({
    	url: web_root + "_modules/_diploms",
    	data: {
    		action: "getAverageMark",
    		id: {$diplom->getId()}
    	},
        cache: false
    }).done(function(data){
    	jQuery("#average_mark").html(data);
    });
    {/if}
</script>

<div id="average_mark" style="color: red; font-size: 150px; position: absolute; right: 5px; "></div>

<div class="control-group">
    {CHtml::activeLabel("kadri_id", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("kadri_id", $diplom, CStaffManager::getPersonsList())}
        <span><a href="{$web_root}_modules/_staff/" target="_blank">
                <img src="{$web_root}images/toupdate.png">
            </a></span>
        {CHtml::error("kadri_id", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("student_id", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("student_id", $diplom, $students)}
        <span><a href="{$web_root}_modules/_students/" target="_blank">
                <img src="{$web_root}images/toupdate.png">
            </a></span>
        {CHtml::error("student_id", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("pract_place_id", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("pract_place_id", $diplom, CTaxonomyManager::getPracticePlacesList())}
        <span><a href="{$web_root}pract_bases.php" target="_blank">
            <img src="{$web_root}images/toupdate.png">
        </a></span>
        {CHtml::error("pract_place_id", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("dipl_name", $diplom)}
    <div class="controls">
        {CHtml::activeTextBox("dipl_name", $diplom)}
        {CHtml::error("dipl_name", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("foreign_lang", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("foreign_lang", $diplom, CTaxonomyManager::getCacheLanguages()->getItems())}
        <span><a href="{$web_root}_modules/_taxonomy/?action=legacy&id=2" target="_blank">
                <img src="{$web_root}images/toupdate.png">
            </a></span>
        {CHtml::error("foreign_lang", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("diplom_confirm", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("diplom_confirm", $diplom, CTaxonomyManager::getCacheDiplomConfirmations()->getItems())}
        {CHtml::error("diplom_confirm", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("recenz_id", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("recenz_id", $diplom, $reviewers)}
        <span><a href="{$web_root}_modules/_staff/" target="_blank">
                <img src="{$web_root}images/toupdate.png">
            </a></span>
        {CHtml::error("recenz_id", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("gak_num", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("gak_num", $diplom, $commissions)}
        {CHtml::error("gak_num", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_act", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("date_act", $diplom, "date_act")}
        {CHtml::error("date_act", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("pages_diplom", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("pages_diplom", $diplom)}
        {CHtml::error("pages_diplom", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("pages_attach", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("pages_attach", $diplom)}
        {CHtml::error("pages_attach", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("average_mark", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("average_mark", $diplom)}
        {CHtml::error("average_mark", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $diplom)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $diplom)}
        {CHtml::error("comment", $diplom)}
    </div>
</div>