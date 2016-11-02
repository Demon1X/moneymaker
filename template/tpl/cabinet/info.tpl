<?php include "header.tpl" ?>

<h1><?= $_TRANS['Info'] ?></h1>

<?=	msg(); ?>

<form action="info" method="post">

<table class="normalTable">
<tr>
<td>Пол</td>
<td>
<input name="sex" type="radio" value="man" checked> Man &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="sex" type="radio" value="woman"> Woman
</td>		
</tr>		

<tr>
<td>Возраст</td>
<td><input name="age" type="text"></td>
</tr>	

<tr>
<td>Страна</td>
<td>
<select name="country" style="width: 110px;">
	<option>Russian</option>
	<option>Ukraina</option>
	<option>China</option>
	<option>USA</option>
	<option>Brazilia</option>
</select>
</td>			
</tr>

<tr>
<td>Город</td>
<td><input name="town" type="text"></td>
</tr>

<tr>
<td>Профессия</td>
<td><input name="profession" type="text"></td>
</tr>

<tr>	
<td>Зарплата</td>
<td><input name="salary" type="text"></td>
</tr>

<tr>	
<td colspan="2" class="td_center">
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save" value="<?= $_TRANS['Save'] ?>">
</td>
</tr>	
</table>	
</form>


<?php include "footer.tpl" ?>