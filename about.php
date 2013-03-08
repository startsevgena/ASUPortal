<?php
include ('authorisation.php');

$pg_title='О системе';
include ('master_page_short.php');

?>
<h3><?php echo $pg_title;?></h3>
<table width=600 bgcolor="#E6E6FF">
  <tr><td>
		<div style="font: 10pt Arial">
		Добро пожаловать в систему управления. <p> 
		Система построена по принципу Web-приложения и для своей работы требует только наличия у пользователя системы
		 любого Интернет- браузера. <p> 
		Все остальные заботы по обслуживанию Ваших запросов возьмет на себя Сервер системы <p>
		
		<b>Цель настоящей системы</b>  помочь пользователям при вводе, обработке и хранении информации, применяемой в учебном процессе. <p> 
		
		В основе системы лежат данные 5 дипломных проектов:<br> 
		-3 проекта из которых представляют попытку создать приемлимую информационную модель системы 
		в разрезе функций: управление ППС, прогнозирование показателей кафедры, управление учебным процессом;<br>
		-2 проекта реализуют примерный макет реализации системы в Интернет- среде <p>&nbsp; </p><p>&nbsp; </p>
		
		<b>Технические характеристики системы: </b><p> 
		Клиент (пользователь) системы
			<li>Аппаратная конфигурация: 400 MHz,  RAM 64 MB, HDD 20 GB, SVGA,
      <br>программная конфигурация: ОС: Win\Linux, 
			любой Интернет-браузер (Internet Explorer 5+, Opera 8+, Mozilla 1+), разрешение экрана монитора от 800*600 пикселей  </li>
		<p> Сервер (поставщик услуг) системы
				<li>Аппаратная конфигурация: 400 MHz,  RAM 128 MB, HDD 40 GB, SVGA,
        <br>программная конфигурация: ОС: Win\Linux, Apache 1.3.27,PHP 4.3.3,
        MySQL 4.1.16, программный комплекс PHPMyAdmin 2.8.0.4</li>
    <div align=right> <font size=-2>(с) <a href=mailto:smart_newline@mail.ru>Агапов Р.Н.</a> </font></div>
    </div>
</td></tr></table>

<div align=left><p> <a href="javascript:history.back()">Вернуться...</a> </div>

<?php include('footer.php'); ?>