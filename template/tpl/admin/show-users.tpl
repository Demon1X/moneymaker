<?php include "header.tpl" ?>
<h2>Пользователи</h2>

<a href="/make-user">Создать пользователя</a>
<!-- <a href="/make-bot">Создать бота</a> -->
<br><br>

<form action='/show-users' method='post'>
<br>
&nbsp;Логин  <input name="login"  type="text" value="<?= @$_SESSION['FILTER']['login']?>">
<!-- &nbsp;Группа <input name="group"  type="text" value="<?= @$_SESSION['FILTER']['group']?>">  -->
&nbsp;E-mail <input name="email"  type="text" value="<?= @$_SESSION['FILTER']['email']?>">
&nbsp;Статус <select name="status">
				<option value=""         <?php if (@$_SESSION['FILTER']['status'] == '')         echo "selected"; ?>>all</option>
				<option value="active"   <?php if (@$_SESSION['FILTER']['status'] == 'active')   echo "selected"; ?>>active</option>
				<option value="inactive" <?php if (@$_SESSION['FILTER']['status'] == 'inactive') echo "selected"; ?>>inactive</option>
             </select>
&nbsp;Реферрер <input name="inviter"  type="text" value="<?= @$_SESSION['FILTER']['inviter']?>">
<input name="token" type="hidden" value="<?= set_token() ?>" />
&nbsp;<input type="submit" name="filter" value="Отфильтровать">
<?php if (isset($_SESSION['FILTER'])) echo "&nbsp;<input type='submit' name='clear_filter' value='Очистить'>"; ?>
</form>
<br><br>

<table class="allUsers">
<tr>
<td>ID</td>
<td>Группа</td>
<td>Логин</td>
<td>E-mail</td>
<td>Статус</td>
<td>Уровень</td>
<td>Реферал</td>
</tr>


<?php
	foreach ($users as $user)
	{
		echo "<tr>";
		echo "<td>{$user['uID']}</td>";
		echo "<td>{$user['uGroup']}</td>";
		echo "<td><a href='/edit-user/{$user['uID']}'>{$user['uLogin']}</a></td>";
		echo "<td>{$user['uMail']}</td>";
		echo "<td>{$user['uStatus']}</td>";
		echo "<td>{$user['uLevel']}</td>";
		echo "<td><a href='/edit-user/{$user['uRef']}'>".get_login($user['uRef'])."</a></td>";
		echo "</tr>";
	}
?>


</table>

<div class="pagination">
<?= pagination ('users') ?>
</div>

<?php include "footer.tpl" ?>