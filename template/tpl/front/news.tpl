<?php include "header.tpl" ?>

<h1><?= $_TRANS['News'] ?></h1>

<?php
	foreach ($news as $novelty)
	{
		echo "<div class='novelty'>";
		echo "<h2>".$novelty['nTopic']."</h2>";
		echo "<b>".$novelty['nTS']."</b><br>";
		echo $novelty['nAnnounce']."<br><br>";
		echo "</div>";
	}

?>

<?php include "footer.tpl" ?>