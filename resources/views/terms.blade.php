<?php 
$business = \App\Model\BusinessSetting::where('key', 'terms_and_conditions')->first();

?>
{!! $business->value !!}