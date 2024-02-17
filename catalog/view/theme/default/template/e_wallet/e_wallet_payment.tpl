<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=extension/payment/e_wallet_payment/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},		
		success: function(d) {
			if(d && typeof d.error != 'undefined'){
				var html = '<div class="alert alert-danger">'+d.error+'<button type="button" class="close" data-dismiss="alert">×</button></div>';
				$('#collapse-checkout-confirm .panel-body').prepend(html);
			}else{
				location = '<?php echo $continue; ?>';
			}
		}		
	});
});
//--></script> 
