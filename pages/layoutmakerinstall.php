<?php

if($loguserid == 0)
	Kill("Only members can install their layouts");

if(isset($_POST['action']) && $_POST['action'] == "Install")
{
	//Changed this to not to use the uploader since it's now a plugin. ~Dirbaio
	//Changed the folder name as it doesnt mix well with rewritten URLS
	if(!is_dir("layoutcrap"))
		mkdir("layoutcrap/");

	$path = "blargboard/layoutcrap/".$loguserid.".css";
	file_put_contents($path, $_POST['css']);
	$path = resourceLink($path);
	$head = "<style>".$_POST['css']."</style>\n".$_POST['header'];
	$foot = $_POST['footer'];

	Query("UPDATE {users} SET postheader = {0}, signature = {1} WHERE id = {2}",
		$head, $foot, $loguserid);

	Report("[b]".$loguser['name']."[/] edited his layout with the layout maker. -> [g]#HERE#?uid=".$userid, 1);

	die(header("Location: ".actionLink("profile", $loguserid)));
}

?>
