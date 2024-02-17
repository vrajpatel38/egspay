<?php echo $header; ?>
<div class="container" id="hprm">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div class="row">
		<?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<h1><?php echo $text_default_heading_title; ?></h1>
			<div class="clearfix"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-reseller"
				  class="form-horizontal">
				<fieldset>
					<legend class="text-center"><?php echo $text_reseller_detail; ?></legend>

					<div class="subtitle"><?php echo $text_default_instruction; ?></div>



					<div>
						<div class="<?php echo $hpwd_reseller_management_toggle_socmed=='1' ? '':'hide'; ?>">
							<div class="instant-confirmation col-sm-12">
								<?php if ($hpwd_reseller_management_whatsapp) { ?>
									<a href="https://api.whatsapp.com/send?phone=<?php echo $hpwd_reseller_management_whatsapp_country; ?><?php echo $hpwd_reseller_management_whatsapp; ?>&text=<?php echo $text_whatsapp; ?>"
									   target="_blank" class="btn-confirm wa"><i class="fa fa-whatsapp"></i>
										Whatsapp</a>
								<?php } ?>
								<?php if ($hpwd_reseller_management_messenger) { ?>
									<a href="https://m.me/<?php echo $hpwd_reseller_management_messenger; ?>" target="_blank"
									   class="btn-confirm fb"><i class="fa fa-facebook"></i> Facebook</a>
								<?php } ?>
								<?php if ($hpwd_reseller_management_telegram) { ?>
									<a href="https://t.me/<?php echo $hpwd_reseller_management_telegram; ?>" target="_blank"
									   class="btn-confirm tg"><i class="fa fa-telegram"></i> Telegram</a>
								<?php } ?>
							</div>
							<div class="separat|| $col-sm-12" style="margin-top:-10px">
								<div class="left"></div>
								<div class="center"> <?php echo $text_form_detail; ?></div>
								<div class="right"></div>
							</div>
						</div>
						<h2><?php echo $text_heading_info; ?></h2>

						<div class="form-group required">
							<label class="col-sm-3" for="input-firstname">
								<h4><?php echo $entry_firstname; ?></h4>
							</label>
							<div class="col-sm-9">
								<input type="text" name="firstname" <?php echo $logged ? 'disabled' : ''; ?>
									   value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname"
									   class="form-control"/>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-lastname">
								<h4><?php echo $entry_lastname; ?></h4>
								<p class="help"><?php echo $help_lastname; ?></p>
							</label>
							<div class="col-sm-9">
								<input type="text" name="lastname" <?php echo $logged ? 'disabled' : ''; ?> value="<?php echo $lastname; ?>"
									   placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control"/>
							</div>
						</div>
						<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>"/>
					   <?php if ($id_card_entry == 1 or  id_card_entry == 2) { ?>
						<div class="form-group required">
							<label class="col-sm-3" for="input-ktp">
								<h4><?php echo $entry_ktp; ?></h4>
								<p class="help"><?php echo $help_ktp; ?></p>
							</label>
							<div class="col-sm-9">
								<input type="number" onkeypress="return NIKmax(this)" onkeyup="return validateMAX(this)"
									   max="16" name="no_nik" value="" placeholder="<?php echo $entry_ktp; ?>" id="input-ktp"
									   class="form-control"/>

							</div>
						</div>

						<?php } else { ?>
							 <input type="hidden" name="no_nik" value=""/>
						<?php } ?>

						<div class="form-group required">
							<label class="col-sm-3" for="input-email">
								<h4><?php echo $entry_email; ?></h4>
								<p class="help"><?php echo $help_email; ?></p>
							</label>
							<div class="col-sm-9">
								<input type="text" name="email" <?php echo $logged ? 'disabled' : ''; ?> value="<?php echo $email; ?>"
									   placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control"/>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-password">
								<h4><?php echo $entry_password; ?></h4>
							</label>
							<div class="col-sm-9">
								<input type="password" name="password" <?php echo $logged ? 'disabled' : ''; ?>
									   placeholder="******" id="input-password" class="form-control"/>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-password-confirm">
								<h4><?php echo $entry_password_confirm; ?></h4>
							</label>
							<div class="col-sm-9">
								<input type="password" name="confirm" <?php echo $logged ? 'disabled' : ''; ?>
									   placeholder="******" id="input-password-confirm" class="form-control"/>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-telephone">
								<h4><?php echo $entry_telephone; ?></h4>
							</label>
							<div id="country_code" class="col-sm-9">
								<div class="col-sm-1" style="padding: 0;">
									<span class="marker-down"></span>
									<select data-live-search="true" data-selected="<?php echo $default_country; ?>" name="default_country" class="country_code">
                                    </select>
								</div>
								<div class="col-sm-5">
									<input name="telephone" placeholder="" type="number" value="<?php echo $telephone; ?>" id="input-telephone"
										   class="form-control">
								</div>
							</div>
						</div>

					 <?php if ($social_media_profile) { ?>

						<h2><?php echo $text_heading_social_media; ?></h2>

						<div class="form-group required">
							<label class="col-sm-3" for="input-telephone">
								<h4><?php echo $entry_facebook; ?></h4>
							</label>
							<div class="col-sm-9">
								<div class="input-group">
									<span style="width:150px" class="input-group-addon">https://facebook.com/</span>
									<input placeholder="<?php echo $entry_facebook; ?>" type="text" name="social_media_facebook"
										   value="<?php echo $social_media_facebook; ?>" id="input-telephone"
										   class="form-control"/>
								</div>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-telephone">
								<h4><?php echo $entry_twitter; ?></h4>
							</label>
							<div class="col-sm-9">
								<div class="input-group">
									<span style="width:150px" class="input-group-addon">https://twitter.com/</span>
									<input placeholder="<?php echo $entry_twitter; ?>" type="text" name="social_media_twitter"
										   value="<?php echo $social_media_twitter; ?>" id="input-telephone"
										   class="form-control"/>
								</div>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-3" for="input-telephone">
								<h4><?php echo $entry_instagram; ?></h4>
							</label>
							<div class="col-sm-9">
								<div class="input-group">
									<span style="width:150px" class="input-group-addon">https://instagram.com/</span>
									<input placeholder="<?php echo $entry_instagram; ?>" type="text" name="social_media_instagram"
										   value="<?php echo $social_media_instagram; ?>" id="input-telephone"
										   class="form-control"/>
								</div>
							</div>
						</div>

						<?php } else { ?>
						<input type="hidden" name="social_media_instagram" value="" />
						<input type="hidden" name="social_media_facebook" value="" />
						<input type="hidden" name="social_media_twitter" value="" />
						<?php } ?>


						<h2><?php echo $text_heading_reseller; ?></h2>
						<div class="form-group required">
							<label class="col-sm-3" for="input-member-type">
								<h4><?php echo $entry_customer_group; ?></h4>
							</label>
							<div class="col-sm-9">
								<select name="customer_group_id" id="input-member-type" class="form-control">
									<option value="0"><?php echo $text_selection_reseller; ?></option>
									<?php foreach ($customer_groups_active as $member) { ?>
										<?php if ($member['customer_group_id'] == current_customer_group) { ?>
											<option value="<?php echo $member['customer_group_id']; ?>" selected="selected"><?php echo $member['name']; ?> (<?php echo $text_your_current_group; ?>)</option>
										<?php } else { ?>
											<option value="<?php echo $member['customer_group_id']; ?>"><?php echo $member['name']; ?></option>
										{% endif%}
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-country-id">
								<h4><?php echo $entry_country; ?></h4>
							</label>
							<div class="col-sm-9">
								<select name="country_id" id="input-country-id" class="form-control">
									<option value="0"><?php echo $text_select; ?></option>
									<?php foreach ($countries as $country) { ?>
										<?php if ($country['country_id'] == country_id) { ?>
											<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>

						 <div class="form-group required">
							<label class="col-sm-3" for="input-zone-id">
								<h4><?php echo $entry_zone; ?></h4>
							</label>
							<div class="col-sm-9">
								<select name="zone_id" id="input-zone-id" class="form-control">
									<option value="0"><?php echo $text_select; ?></option>
								</select>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-sm-3" for="input-address">
								<h4><?php echo $entry_address; ?></h4>
							</label>
							<div class="col-sm-9">
								<textarea name="address" placeholder="<?php echo $entry_address; ?>" id="input-address"
										  class="form-control" rows="6" cols="100"><?php echo $address; ?> </textarea>
							</div>
						</div>


						<?php if (!$is_reseller) { ?>
						<div class="form-group required">
							<label class="col-sm-3">
								<h4><?php echo $entry_lampiran; ?></h4>
								<p class="help"><?php echo $hpwd_reseller_management_text_attachment; ?></p>
							</label>
							<div class="col-sm-3">
								<button type="button" id="input-attachment" data-loading-text="<?php echo $text_loading; ?>"
										class="btn btn-primary btn-block hprm-button"><i class="fa fa-upload"></i>
									<?php echo $button_upload; ?></button>
							</div>
							<input type="hidden" name="lampiran" value="<?php echo $lampiran; ?>" id="upload_lampiran"/>
							<div class="col-sm-3">
								<img id="lampiran-preivew" src="" alt="">
							</div>
						</div>

						<?php } else { ?>
						   <input type="hidden" name="lampiran" value=""/>
						<?php } ?>

						<div class="form-group">
							<label class="col-sm-3"></label>
							<div class="col-sm-9 text-right button-submit">
								<button style="text-transform: uppercase;" type="submit" class="btn btn-primary hprm-button" id="button-register"> <?php echo $is_reseller ? text_edit_reseller_data : text_default_button_register; ?></button>
							</div>
						</div>
					</div>

				</fieldset>
			</form>

			<?php echo $content_bottom; ?>
		</div>
		<?php echo $column_right; ?>
	</div>
</div>

<style>
	.help {
		color: #888;
		font-weight: normal;
		font-size: 11px;
		display: block;
		margin-top: 3px;
		margin-bottom: 10px;
		font-style: italic;
	}
</style>
<script type="text/javascript">
	$('#button_tgl_bayar').on('click', function () {
		$('.date').datetimepicker({
			pickTime: false
		});
	});
</script>
<script type="text/javascript">
	const availabel_countries = <?php echo $availabel_countries; ?>;

	var country_code_prefix = '<?php echo $default_country; ?>';

	$(document).ready(function () {
		fetch("admin/view/javascript/selectpicker/js/countryPhone.json").then(res => res.json()).then(json => {
			$(".country_code").each(function () {
				let defaultValue = $(this).attr('data-selected');
				let options = [];
				json.forEach(country => {
					if (availabel_countries.includes(country.code)) {
						if (defaultValue == country.phone) {
							options.push(
								"<option selected title='+" + country.phone + "' value='" + country.phone + "' >" + country.name + " +" + country.phone + "</option>"
							);
						} else {
							options.push(
								"<option title='+" + country.phone + "' value='" + country.phone + "' >" + country.name + " +" + country.phone + "</option>"
							);
						}
					}
				})
				$(this).html(options);
				$(this).selectpicker();


				if(country_code_prefix) {
					$(this).val(country_code_prefix);
					$(this).selectpicker('refresh');
				}

			})
		});
	});

	$('button[id^=\'input-attachment\']').on('click', function () {
		var node = this;

		$('#form-upload').remove();

		$('body').prepend(
			'<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" id="imgInp" name="file" /></form>'
		);

		$("#imgInp").change(function () {
			readURL(this);
		});

		$('#form-upload input[name=\'file\']').trigger('click');

		timer = setInterval(function () {
			if ($('#form-upload input[name=\'file\']').val() != '') {
				clearInterval(timer);

				$.ajax({
					url: 'index.php?route=account/reseller/upload',
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form-upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function () {
						$(node).button('loading');
					},
					complete: function () {
						$(node).button('reset');
					},
					success: function (json) {
						$('.text-danger').remove();

						if (json['error']) {
							$('input[name=\'code\']').after('<div class="text-danger">' +
								json['error'] + '</div>');
						}

						if (json['success']) {
							$('#filename').html(json['success']);
							$('#filename').addClass("alert alert-danger");

							$('input[name=\'lampiran\']').attr('value', json['code']);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr
							.responseText);
					}
				});
			}
		}, 500);
	});

	$('select[name=\'country_id\']').on('change', function() {

		$('select[name=\'zone_id\']').val(0)

		$.ajax({
			url: 'index.php?route=account/account/country&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'country_id\']').prop('disabled', true);
				$('select[name=\'zone_id\']').prop('disabled', true);
			},
			complete: function() {
				$('select[name=\'country_id\']').prop('disabled', false);
				$('select[name=\'zone_id\']').prop('disabled', false);
			},
			success: function(json) {
				html = '<option value=""><?php echo $text_selection_zone; ?></option>';

				if (json['zone'] && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}

				$('select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
});

$('select[name=\'country_id\']').trigger('change');

	var is_reseller = parseInt('<?php echo $is_reseller; ?>');

	$('#button-register').on('click', function (e) {
		e.preventDefault();

		$('input[name=\'telephone\']').keyup();

		$.ajax({
			url: 'index.php?route=account/reseller/save',
			type: 'post',
			data: $('#form-reseller').serialize(),
			dataType: 'json',
			beforeSend: function () {
				$('#button-register').button('loading');
				$('.text-danger').remove();
				$(".input-group").removeClass("has-error");
				$(".form-group").removeClass("has-error");
				$('.alert-danger').remove();
			},
			complete: function () {
				$('#button-register').button('reset');
			},
			success: function (json) {

				if(!is_reseller) {
					if ($('input[name=lampiran]').val()) {
						$('#filename').removeClass('alert-danger');
						$('#filename').addClass('alert-success')
						$('#filename').remove();
						;
					} else {
						$('.alert-danger').remove();
					}
				}

				$('.text-danger').remove();
				$(".input-group").removeClass("has-error");
				$(".col-sm-9").removeClass("has-error");
				$(".form-group").removeClass("has-error");

				if (json['error']) {
					for (i in json['error']) {
						if (i == 'warning') {
							continue;
						}

						var element = $('[name="' + i + '"]');

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().addClass('has-error');
							$(element).parent().after('<div class="text-danger">' + json['error'][
								i
								] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] +
								'</div>');
							$(element).parent().addClass('has-error');
							;
						}
					}

					if (json['error']['warning']) {
						$('.breadcrumb').after(
							'<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' +
							json['error']['warning'] +
							' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'
						);
						$('.alert-danger').fadeIn('slow');
						$('html, body').animate({
							scrollTop: 0
						}, 'slow');
					}

				}

				if (json['success']) {

					$('.breadcrumb').after(
						'<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' +
						json['success'] +
						' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'
					);

					$('.alert-danger').fadeIn('slow');
					$('html, body').animate({
						scrollTop: 0
					}, 'slow');
					$('#filename').removeClass('alert alert-danger');
					$('#filename').html('');

				   // $('#form-reseller')[0].reset();

					setTimeout(function () {
						if (json.logout_url) {
							window.location.href = json.logout_url
						}
					}, 5000)

				}
			}
		});
	});

	$(document).on('keyup', 'input[name=\'telephone\']', function() {
		let telephone = $(this).val().trim();
		let select_prefix = $('[name=\'default_country\'] option:selected').val();
		let length_prefix = select_prefix.length;

		if(telephone.substr(0, length_prefix) == select_prefix){
			$(this).val(telephone.substr(length_prefix));
		}

		if(telephone.substr(0,1) == "0"){
			$(this).val(telephone.substr(1,telephone.length-1));
		}

	});

	$('select[name=\'customer_group_id\']').on('change', function () {
		if ($.isNumeric($('select[name=\'customer_group_id\']').val())) {
			$('input[name=\'group_name\']').val($('select[name=\'customer_group_id\'] option:selected').text());
		}
	});

	$('select[name=\'customer_group_id\']').trigger('change');

	function NIKmax(elm) {
		var length = ($(elm).val()).length;
		if (length >= 16) {
			$(elm).val($(elm).val());
			return false;
		}
	}

	function validateMAX(elm) {
		var length = ($(elm).val()).length;

		if (length >= 16) {
			$(elm).val(($(elm).val()).substring(0, 15));
			return false;
		}

	}

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#lampiran-preivew').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>
<?php echo $footer; ?>
