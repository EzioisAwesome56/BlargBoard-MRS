<?php

$cssTemplate = ".mainbar[ID] div.sig[ID]
{
	background: [BACKGROUND];
	margin: 15px;
	padding: 15px;
	border: [BORDER];
	border-radius: [RADIUS];
	color: [COLOR];
font-family: \"[FONT]\";
	
}
";

$markupTemplateA = "";
$markupTemplateB = "<div class=\"sig[ID]\">[SIG]</div>";

$parameters = array
(
	"ID" => array("label"=>"User ID", "type"=>"int"),
	"BACKGROUND" => array("label"=>"Background", "type"=>"background", "default"=>"#AA0000"),
	"BORDER" => array("label"=>"Border", "type"=>"border", "default"=>"1px solid #FFA500"),
	"RADIUS" => array("label"=>"Corner radius", "type"=>"range", "min"=>"0", "max"=>"16", "default"=>"8", "pxem"=>"1"),
	"COLOR" => array("label"=>"Color", "type"=>"color", "default"=>"#FFFFFF"),
	"FONT" => array("label"=>"Font", "type"=>"font", "default"=>"Lucida Sans Unicode"),
	//"SIG" => array("label"=>"Sig text", "type"=>"font", "default"=>"My Sig"),

	"MARGINTYPE" => array("hidden"=>"1"),
	"PADDINGTYPE" => array("hidden"=>"1"),
	"RADIUSTYPE" => array("hidden"=>"1"),
);

?>
