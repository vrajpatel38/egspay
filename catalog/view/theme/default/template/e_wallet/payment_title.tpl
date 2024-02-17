<span class="hide_next"><?php echo $title; ?></span></label><label class="new_html_dynamic" style="display:none">
<div class="form-group">
	<input name="use_e_wallet" id="use-e_wallet" type="checkbox"> <label for="use-e_wallet"><?php echo $new_title; ?> </label>
	<?php if($remain_payment <= 0) { ?><input type="radio" name="payment_method" id="e_wallet_payment_method" value="e_wallet_payment" style="display:none;"/><?php } ?>
	<div class="use_e_wallet_text" style="display:none;"><?php echo $wallettext; ?></div>
</div>
<script type="text/javascript">
	var other_theme = <?php echo $other_theme; ?>;
	$(".hide_next").parents("label").find("input[type=radio]").remove();
	$(".new_html_dynamic").show();
	wradio = $(".hide_next").parents(".radio").addClass("wallet_container");
	oradio = wradio.next('.radio');
	if(oradio.length) oradio.find("input[type=radio]").prop("checked", true).trigger("change");
	//$(".hide_next").closest(".radio").addClass("wallet_container");
	//$(".hide_next").closest(".radio").css({"padding-left":"1px"});
	//$(".wallet_container + .radio").find("input[type=radio]").prop("checked", true).trigger("click");
	$("#use-e_wallet").change(function(){
		if($(this).prop("checked")){
			$(".use_e_wallet_text").show();
			if($("#e_wallet_payment_method").length){
	    	  // console.log(other_theme);
				$("#e_wallet_payment_method").prop("checked",true);
				$("#collapse-payment-method .radio").not(wradio).hide();
				if(other_theme) $(".checkout-payment-methods .radio").not(wradio).hide();
			}
		}else{
			$(".use_e_wallet_text").hide();
			$("#e_wallet_payment_method").prop("checked",false);
			$("#collapse-payment-method .radio").show();
			if(other_theme) $(".checkout-payment-methods .radio").show();
		}
		if(other_theme){ 
			val = $("[name=\'payment_method\']:checked").val();
			$(document).trigger("journal_checkout_payment_changed", val);
		}
	});
</script>
<style type="text/css">
	.new_html_dynamic{clear: both;display: inline-block !important;}
	.use_e_wallet_text{display: none;clear: both;}
	.use_e_wallet_text  p{display: block !important;}
	.hide_next + input[name="payment_method"]{display: none;}
	.new_html_dynamic input{display: inline-block;vertical-align: middle;margin-right: 4px;margin-top: 0px;margin-bottom: 0px;}
	.new_html_dynamic label{display: inline-block !important;vertical-align: middle;margin-top: 0px;margin-bottom: 0px;}
	span.hide_next{display: none;}
	.wallet_container label{padding-left: 0;}
	.new_html_dynamic .form-group{margin-bottom: 10px;}
</style>