<?php
	$pg_title="История кафедры";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}
	?>

<div class="main"><?php echo $pg_title?><br /></div>
<table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
<tbody>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td width="1000" valign="top"><!--[if gte mso 9]><xml> Normal   0               false   false   false      RU   X-NONE   X-NONE                                                     MicrosoftInternetExplorer4 </xml><![endif]--><!--[if gte mso 9]><xml> </xml><![endif]--> <!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:"Обычная таблица";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-qformat:yes;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:minor-fareast;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;}
</style>
<![endif]-->
<p>Датой основания кафедры принято считать 1972 год, когда под руководством д.т.н., профессора Юсупова Ислам Юсуповича была организована кафедра &laquo;Автоматизация производственных процессов&raquo;. Позже, в 1976 году &laquo;Автоматизацию производственных процессов&raquo; переименовали в &laquo;Автоматизированные системы управления&raquo;. Это название кафедра сохранила и по сегодняшний день.</p>
<p>В 1974 году кафедра производит первый набор студентов на специальность &laquo;Автоматизированные системы управления&raquo;. Первый выпуск состоялся в июне теперь уже очень далекого 1979 года.</p>
<p>В 1992 году АСУ переходит на многоуровневую систему образования и открывает бакалавриат по направлению &laquo;Информатика и вычислительная техника&raquo;. Год спустя, в 1993, открывается набор на новую специальность &laquo;Информационные системы в экономике&raquo;.</p>
<p>В 1996 году кафедра открывает магистратуру по направлениям &laquo;Распределенные автоматизированные системы&raquo; и &laquo;Информационные и управляющие системы&raquo;.</p>
<p>В 2000 году открывается специальность &laquo;Прикладная информатика в экономике&raquo;.</p>
<p>На сегодняшний день заведующим кафедрой АСУ является Куликов Геннадий Григорьевич, возглавивший коллектив кафедры в 1992 году.</p>
<p>При активном содействии УМПО на кафедре АСУ была организована учебно-научная лаборатория, в которой установлены корпоративные информационные системы BAAN и Галактика. Помощь, оказываемая УМПО, помогает АСУ неустанно повышать квалификацию выпускаемых специалистов.&nbsp;</p>
<p>Говоря о материальной базе кафедры, следует упомянуть, что сейчас АСУ имеет 5 дисплейных классов и 6 лабораторий, оборудованных современной вычислительной техникой, собственную локальную вычислительную сеть с выходом в Internet и выделенным сервером, богатый комплекс специализированного программного обеспечения.</p>
<p>На кафедре за время ее существования было подготовлено 9 докторов наук и более 30 кандидатов. Обращаясь к истории, следует отметить, что первый диссертант АСУ, Черняховская Лилия Рашидовна, успешно защитилась в 1977 году. Первая докторская диссертация была защищена Мироновым Валерием Викторовичем в 1995 году.</p>
<p>На сегодняшний день АСУ - кафедра со сложившимися традициями, укомплектованной учебно-материальной базой и квалифицированным персоналом. В городах Кумертау, Белорецк и Ишимбай успешно функционируют филиалы, представительства кафедры, выпускающие специалистов по специальности ПИЭ.</p>
<p>Кафедра АСУ непрерывно развивается, налаживает и укрепляет тесное сотрудничество с производственными предприятиями РБ, высшими учебными заведениями и научно-исследовательскими институтами России и зарубежья, активно участвует в международных программах, грантах на совместное исследование в области автоматизации управления и диагностики ГТД, грантах на зарубежные стажировки от президента РФ и Королевского Общества Великобритании, грантах НАТО на развитие научных связей. Преподаватели нашей кафедры читают лекции в таких зарубежных вузах, как Карлсруэ (Германия), Нанкинский аэрокосмический университет (Китай), Университет г. Шеффилда (Великобритания), куда они регулярно выезжают на стажировки. Имеется практика приема преподавателей из-за рубежа в Уфе.</p>
</td>
<!-- END второго абзаца                          -->
</tr>
</tbody>
</table>

<?php
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include('footer.php'); 
	?>