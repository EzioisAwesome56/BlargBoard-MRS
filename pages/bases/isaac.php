<?php

$cssTemplate = "";

$markupTemplateA = "<style>.table[ID]{
  background-image: url('http://isaacsplace.x10.bz/BackgroundBody.png');
 background-attachment: fixed;
 font-family: Arial, Verdana;
 color: #FFFFFF!important;
 border: 1px solid #000;
padding: 10px 9px;
border-radius: 10px;
} 
.sidebar[ID], .topbar[ID]_1 {
 border-right: 1px solid #000;
 padding: 4px 7px;
background-image: url('http://isaacsplace.x10.bz/background.jpg');
 margin: 25px;
 border-radius: 10px;
}

.topbar[ID]_1, .topbar[ID]_2 {
 border-bottom: 1px solid #000;
background-image: url('http://isaacsplace.x10.bz/background.jpg');
 margin: 25px;
 border-radius: 10px;
padding: 10px 9px;

}

.mainbar[ID] > div.CHANGEDIVNAME{
 color:#FFFFFF!important;
background-image: url('http://isaacsplace.x10.bz/background.jpg');
 padding: 15px;
 margin: 25px;
 border-radius: 10px;
 max-width: 1000px;
}

}
.mainbar[ID] div.quote{

}
.mainbar[ID] div.quotecontent{

}
.mainbar[ID] div.geshi{

}
.mainbar[ID] div.spoiler{

}
.mainbar[ID] div.spoiler button{

}
.mainbar[ID] div.spoiled{

}

</style><div class="CHANGEDIVNAME">";
$markupTemplateB = "</div>
<div class="CHANGEDIVNAME">
Layout Design made by Isaac Savereno,[user = 45].
 </div>";

$parameters = array
(
	"ID" => array("label"=>"User ID", "type"=>"int"),
);

?>
