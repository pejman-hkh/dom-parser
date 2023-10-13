<?
if( $isAdminLogined ) {
?>
<div class="widget">
<h2>آنلاین</h2>
<?=@count( (array)$onlines )?>
<div class="overflow">
<ul>
<? foreach( $onlines as $online ) {?>
<li> <a href="<?=baseUrl?>panel/devices?userid=<?=$online->userid?>"><?=$online->user->username?></a> / <?=count($online->devices)?></li>
<? } ?>
</ul>
</div>
</div>

<?php
foreach( $arrays as $arr ) {
	print_r( $arr );
}
?>
<div class="ddd"></div>