<?php // content="text/plain; charset=utf-8"
//
// Basic example on how to use custom tickmark feature to have a label
// at the start of each month.
//
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');
require_once ('jpgraph/jpgraph_utils.inc.php');

// 
// Create some random data for the plot. We use the current time for the
// first X-position
//
$datay = array();
$datax = array();
$ts = time();
$n=15; // Number of data points

//for($i=0; $i < $n; ++$i ) {
//    $datax[$i] = $ts+$i*700000; 
//    $datay[$i] = rand(5,60);
//}

$datax[0] = 1;
$datax[1] = 2;
$datax[2] = 3;
$datax[3] = 4;
$datax[4] = 5;
$datax[5] = 6;
$datax[6] = 7;
$datax[7] = 8;
$datax[8] = 9;
$datax[9] = 10;
$datax[10] = 11;
$datax[11] = 12;
$datax[12] = 13;
$datax[13] = 14;
$datax[14] = 15;

$datay[0] = 1;
$datay[1] = 2;
$datay[2] = 3;
$datay[3] = 4;
$datay[4] = 5;
$datay[5] = 6;
$datay[6] = 7;
$datay[7] = 2;
$datay[8] = 9;
$datay[9] = 10;
$datay[10] = 11;
$datay[11] = 12;
$datay[12] = 13;
$datay[13] = 14;
$datay[14] = 15;


// Now get labels at the start of each month
$dateUtils = new DateScaleUtils();
list($tickPositions,$minTickPositions) = $dateUtils->GetTicks($datax);

// We add some grace to the end of the X-axis scale so that the first and last
// data point isn't exactly at the very end or beginning of the scale
$grace = 5;
$xmin = $datax[0]-$grace;
$xmax = $datax[$n-1]+$grace;

//
// The code to setup a very basic graph
//
$graph = new Graph(600,300);

//
// We use an integer scale on the X-axis since the positions on the X axis
// are assumed to be UNI timestamps
$graph->SetScale('intlin',0,0,$xmin,$xmax);
$graph->title->Set('Basic example with manual ticks');
$graph->title->SetFont(FF_ARIAL,FS_NORMAL,12);

//
// Make sure that the X-axis is always at the bottom of the scale
// (By default the X-axis is alwys positioned at Y=0 so if the scale
// doesn't happen to include 0 the axis will not be shown)
$graph->xaxis->SetPos('min');

// Now set the tic positions
//$graph->xaxis->SetTickPositions($tickPositions,$minTickPositions);

// The labels should be formatted at dates with "Year-month"
//$graph->xaxis->SetLabelFormatString('My',true);

// Use Ariel font
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,9);

// Add a X-grid
$graph->xgrid->Show();

// Create the plot line
$p1 = new LinePlot($datay,$datax);
$p1->SetColor('teal');
$graph->Add($p1);

// Output graph
$graph->Stroke();

?>


