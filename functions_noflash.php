<?php 
if (!defined('ABSPATH')) die('SECURITY');

// auxiliary functions
function do_easing($what=null)
{
$eases=array('linear',
        'backEaseIn',
        'backEaseOut',
        'backEaseInOut',
        'bounceEaseIn',
        'bounceEaseOut',
        'circEaseIn',
        'circEaseOut',
        'circEaseInOut',
        'cubicEaseIn',
        'cubicEaseOut',
        'cubicEaseInOut',
        'elasticEaseIn',
        'elasticEaseOut',
        'expoEaseIn',
        'expoEaseOut',
        'expoEaseInOut',
        'quadEaseIn',
        'quadEaseOut',
        'quadEaseInOut',
        'quartEaseIn',
        'quartEaseOut',
        'quartEaseInOut',
        'quintEaseIn',
        'quintEaseOut',
        'quintEaseInOut',
        'sineEaseIn',
        'sineEaseOut',
        'sineEaseInOut');
	$easeselect="<select name='nf_ease[]'>";
	foreach ($eases as $ease)
	{
		if ($what != null && $what==$ease)
		{
			$easeselect.="<option value=$ease selected>$ease</option>";
		}
		else
		{
			$easeselect.="<option value=$ease>$ease</option>";
		}
	}
	$easeselect.="</select>";
	return $easeselect;
}
function do_transition($what=null)
{
$fxs=array('random',
	'rotate-tiles',
    'rotate-tiles-reverse',
    'flip-tiles-horizontal',
    'flip-tiles-vertical',
    'iris',
    'iris-reverse',
    'fade-tiles',
    'fade-grow-tiles',
    'fade-shrink-tiles',
    'shrink-tiles',
    'grow-tiles',
    'shrink-tiles-horizontal',
    'grow-tiles-horizontal',
    'grow-tiles-vertical',
    'grow-fade-tiles-horizontal',
    'grow-fade-tiles-vertical',
    'shrink-tiles-vertical',
    'move-tiles-vertical-down',
    'move-tiles-vertical-up',
    'move-tiles-vertical-up-down',
    'move-tiles-horizontal-right',
    'move-tiles-horizontal-left',
    'move-tiles-horizontal-left-right',
    'move-fade-tiles-vertical-down',
    'move-fade-tiles-vertical-up',
    'move-fade-tiles-vertical-up-down',
    'move-fade-tiles-horizontal-right',
    'move-fade-tiles-horizontal-left',
    'move-fade-tiles-horizontal-left-right',
    'fly-top-left',
    'fly-bottom-left',
    'fly-top-right',
    'fly-bottom-right',
    'fly-left',
    'fly-right',
    'fly-top',
    'fly-bottom',
    'pan-top-left',
    'pan-top-right',
    'pan-bottom-right',
    'pan-bottom-left',
    'pan-left',
    'pan-right',
    'pan-top',
    'pan-bottom');
	$fxselect="<select name='nf_fx[]'>";
	foreach ($fxs as $fx)
	{
		if ($what != null && $what==$fx)
		{
			$fxselect.="<option value=$fx selected>$fx</option>";
		}
		else
		{
			$fxselect.="<option value=$fx>$fx</option>";
		}
	}
	$fxselect.="</select>";
	return $fxselect;
}

function do_ordering($what=null)
{
$ords=array('checkerboard',
'diagonal-top-left',
'diagonal-top-right',
'diagonal-bottom-left',
'diagonal-bottom-right',
'rows',
'rows-reverse',
'columns',
'columns-reverse',
'rows-first',
'rows-first-reverse',
'columns-first',
'columns-first-reverse',
'spiral-top-left',
'spiral-top-right',
'spiral-bottom-left',
'spiral-bottom-right',
'spiral-top-left-reverse',
'spiral-top-right-reverse',
'spiral-bottom-left-reverse',
'spiral-bottom-right-reverse',
'random',
'up-down',
'up-down-reverse',
'left-right');
	$ordselect="<select name='nf_ord[]'>";
	foreach ($ords as $ord)
	{
		if ($what != null && $what==$ord)
		{
			$ordselect.="<option value=$ord selected>$ord</option>";
		}
		else
		{
			$ordselect.="<option value=$ord>$ord</option>";
		}
	}
	$ordselect.="</select>";
	return $ordselect;
}
function do_delay($what=null)
{
	if ($what!=null)
		$del="<input type='text' size='5' name='nf_delay[]' value=$what />";
	else
		$del="<input type='text' size='5' name='nf_delay[]' value='5'/>";
	return($del);
}
function do_duration($what=null)
{
	if ($what!=null)
		$dur="<input type='text' size='5' name='nf_duration[]' value=$what />";
	else
		$dur="<input type='text' size='5' name='nf_duration[]' value='2'/>";
	return($dur);
}
function do_rows($what=null)
{
	if ($what!=null)
		$rows="<input type='text' size='5' name='nf_rows[]' value=$what />";
	else
		$rows="<input type='text' size='5' name='nf_rows[]' value='1'/>";
	return($rows);
}
function do_columns($what=null)
{
	if ($what!=null)
		$col="<input type='text' size='5' name='nf_columns[]' value=$what />";
	else
		$col="<input type='text' size='5' name='nf_columns[]' value='1'/>";
	return($col);
}
function do_overlap($what=null)
{
	if ($what!=null)
		$ovr="<input type='text' size='5' name='nf_overlap[]' value=$what />";
	else
		$ovr="<input type='text' size='5' name='nf_overlap[]' value='0.9'/>";
	return($ovr);
}
function do_random($what=null)
{
	if ($what!=null)
	{
		if ($what=='0')
		{
		$ovr="<input type='radio' name='nf_random' value='0' checked>No</input>&nbsp;&nbsp;&nbsp;";
		$ovr.="<input type='radio' name='nf_random' value='1'>yes</input>";
		}
		else
		{
		$ovr="<input type='radio' name='nf_random' value='0'>No</input>&nbsp;&nbsp;&nbsp;";
		$ovr.="<input type='radio' name='nf_random' value='1' checked>yes</input>";
		}
	}
	else
		{
		$ovr="<input type='radio' name='nf_random' value='0' checked>No</input>&nbsp;&nbsp;&nbsp;";
		$ovr.="<input type='radio' name='nf_random' value='1'>yes</input>";
		}
	return($ovr);
}
?>