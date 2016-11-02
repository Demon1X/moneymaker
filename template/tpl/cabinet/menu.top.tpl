<div class="topMenu">
<a href="/main"><?= $_TRANS['Main'] ?></a>
<a href="/about"><?= $_TRANS['About'] ?></a>
<a href="/news"><?= $_TRANS['News'] ?></a>

<a href="/faq"><?= $_TRANS['FAQ'] ?></a>
<a href="/support"><?= $_TRANS['Support'] ?></a>

<?php 
	if (is_user()) 
	{	
		echo "<a href='/logout'>{$_TRANS['Logout']}</a>";
	}
	else 
	{ 
		echo "<a href='/login'>{$_TRANS['Entry']}</a> ";
		echo "<a href='/signup'>{$_TRANS['Sign up']}</a>";
	} 
?>

 | <a href="/test">TEST</a> 
<a href="/lang/en">en</a> /
<a href="/lang/ru">ru</a>

</div>