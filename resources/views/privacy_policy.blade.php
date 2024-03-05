<?php 
$business = \App\Model\BusinessSetting::where('key', 'privacy_policy')->first();

?>
{!! $business->value !!}