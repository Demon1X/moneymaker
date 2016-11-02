<div class="cabinet_menu">
<a href="/cabinet"><?= $_TRANS['Cabinet'] ?></a>
<a href="/add-funds"><?= $_TRANS['Add funds'] ?></a>
<a href="/deposits"><?= $_TRANS['Deposits'] ?></a>
<a href="/withdrawal"><?= $_TRANS['Withdrawal'] ?></a>
<a href="/wallets"><?= $_TRANS['Wallets'] ?></a>
<a href="/friends"><?= $_TRANS['Friends'] ?></a>
<a href="/history"><?= $_TRANS['History'] ?></a>
<a href="/settings"><?= $_TRANS['Settings'] ?></a>
<?php  
	if (is_admin()) echo "<a href='/admin'>".$_TRANS['Administrator']."</a>"; 
?>
</div>