<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

function createGraph($xArray, $yArray, $label, $color, $title, $subject)
{
	createG($xArray, $yArray, $label, $color, $title, $subject, 1);
}

function createGraphNoValues($xArray, $yArray, $label, $color, $title, $subject)
{
	createG($xArray, $yArray, $label, $color, $title, $subject, 0);
}

function createG($xArray, $yArray, $label, $color, $title, $subject, $showValues)
{
	// Create the basic graph
	$graph = new Graph(90*15,300,'auto');    
	$graph->SetScale("textlin");
	$graph->img->SetMargin(40,80,30,40);

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15);

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	$graph->xaxis->SetTickLabels($xArray);

	// Set axis titles and fonts
	$graph->xaxis->title->Set($title);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetColor('black');

	$graph->xaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->SetColor('black');

	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('black');

	//$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set($subject);
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_ARIAL,FS_NORMAL,12);

	// Create the three var series we will combine
	$bplot1 = new BarPlot($yArray);

	if($showValues == 1)
		$bplot1->value->Show();

	// Setup the colors with 40% transparency (alpha channel)
	$bplot1->SetFillColor($color."@0.4");

	// Setup legends
	$bplot1->SetLegend($label);

	// Setup each bar with a shadow of 50% transparency
	$bplot1->SetShadow('black@0.4');

	$gbarplot = new GroupBarPlot(array($bplot1));
	$gbarplot->SetWidth(0.6);
	$graph->Add($gbarplot);

	$imageFileName = "tmp/image".rand(1000, 2000)."png";
	$ourFileHandle = fopen($imageFileName, 'w') or die("can't open file");
	fclose($ourFileHandle);
	$graph->Stroke($imageFileName);

	echo "<img border='0' src='$imageFileName'>";

	//unlink($myFile);
}
?>
