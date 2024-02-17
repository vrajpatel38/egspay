<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">

            <div class="pull-right">
                <button type="submit" form="form-basel" data-toggle="tooltip" title="<?php echo $text_btn_save; ?>"
                        class="btn btn-primary"><?php echo $text_btn_save; ?>
                </button>
            </div>
            <h1><?php echo $heading_title2; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="notification-area">
			<?php if ($changes == 'saved') { ?>
				<div class="alert alert-success"><i
							class="fa fa-exclamation-circle"></i> <?php echo $text_success_changes_saved; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
		
			<?php if ($actioninstall == 'true') { ?>
				<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $text_success_install_table; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>

			<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php } ?>

			<?php if ($success) { ?>
				<div class="alert alert-info"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
		</div>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">

            <div class="panel-wrapper">

                <div class="panel-left">
                    <div class="setting-header">v <?php echo $version; ?></div>
                    <ul class="menu list-unstyled">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog fa-fw"></i> <?php echo $tab_general; ?></a></li>
						<li><a href="#tab-group" data-toggle="tab"><i class="fa fa-users"></i> <?php echo $tab_group; ?></a></li>
						<li><a href="#tab-translation" data-toggle="tab"><i class="fa fa-language fw"></i> <?php echo $tab_translation; ?></a></li>
						<li><a href="#tab-sms" data-toggle="tab"><i class="fa fa-envelope fw"></i> <?php echo $tab_sms; ?></a></li>
						<li><a href="#tab-advance" data-toggle="tab"><i class="fa fa-gears fw"></i> <?php echo $tab_advance; ?></a></li>
						<li><a href="#tab-help" data-toggle="tab"><i class="fa fa-question-circle"></i> <?php echo $tab_help; ?></a></li>
                    </ul>
                </div><!-- .panel-left ends -->

                <div class="panel-right">
                    <div class="store-header">
                        <div class="form-group">

                        </div>
                    </div>


                    <div class="main-content">                  	
                    	<div class="tab-content">
							<div class="tab-pane active" id="tab-general">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-sm-2">
												<a href="<?php echo $reseller_page; ?>" target="_blank" class="btn btn-info btn-sm"><i
															class="fa fa-user"></i> <?php echo $text_reseller_page; ?></a>
											</label>
											<label class="col-sm-2">
												<a href="<?php echo $reseller_list_page; ?>" target="_blank" class="btn btn-warning btn-sm"><i
															class="fa fa-users"></i> <?php echo $text_reseller_list_page; ?></a>
											</label>
										</div>

										<div class="form-group required">
											<label class="col-sm-3" for="status">
												<?php echo $entry_status; ?> 
												<span class="help"><?php echo $help_status; ?> </span>
											</label>
											<div class="col-sm-2">
												<input name="hpwd_reseller_management_status" type="checkbox"
													data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
													data-on-label="<?php echo $text_yes; ?>" <?php if ($hpwd_reseller_management_status) { ?> checked="checked" <?php } ?>>
											</div>
										</div>

										<div class="form-group required">
											<label class="col-sm-3" for="input-lastname">
												<?php echo $entry_reseller_group; ?>
												<span class="help"><?php echo $entry_reseller_group_sub; ?></span>
											</label>
											<div class="col-sm-9">
												<div class="well">
													<?php foreach ($customer_groups as $group) { ?>
														<?php if ($customer_group_id != $group['customer_group_id']) { ?>
															<div class="checkbox">
																
															</div>
														<?php } ?>
													<?php } ?>
												</div>

												<?php if ($error_customer_group) { ?>
													<div class="text-danger"><?php echo $error_customer_group; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3" for="color-scheme">
												<?php echo $text_toplink; ?>
												<span class="help"><?php echo $help_toplink; ?></span>
											</label>
											<div class="col-sm-2">
												<input name="hpwd_reseller_management_top_link" type="checkbox"
													data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
													data-on-label="<?php echo $text_yes; ?>" <?php if ($hpwd_reseller_management_top_link) { ?> checked="checked" <?php } ?>>
											</div>
										</div>

										<div class="form-group required">
											<label class="col-sm-3" for="color-scheme">
												<?php echo $text_bottom_link; ?>
												<span class="help"><?php echo $help_bottom_link; ?></span>
											</label>
											<div class="col-sm-2">
												<input name="hpwd_reseller_management_bottom_link" type="checkbox"
													data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
													data-on-label="<?php echo $text_yes; ?>" <?php if ($hpwd_reseller_management_bottom_link) { ?> checked="checked" <?php } ?>>
											</div>
										</div>

										<div class="form-group required">
											<label class="col-sm-3" for="color-scheme">
												<?php echo $text_auto_increase; ?>
												<span class="help"><?php echo $help_auto_increase; ?></span>
											</label>
											<div class="col-sm-2">
												<input name="hpwd_reseller_management_auto_increase" type="checkbox"
													data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
													data-on-label="<?php echo $text_yes; ?>" <?php if ($hpwd_reseller_management_auto_increase) { ?> checked="checked" <?php } ?>>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3" for="input-lastname">
												<?php echo $entry_attachment_required; ?>
												<span class="help"><?php echo $help_attachment_required; ?></span>
											</label>
											<div class="col-sm-9">
												<input <?php echo $hpwd_reseller_management_attachment_required=="1" ? 'checked' : ''; ?>
														name="hpwd_reseller_management_attachment_required" type="checkbox"
														data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
														data-on-label="<?php echo $text_yes; ?>">
											</div>
										</div>

										<div class="form-group required">
											<label class="col-sm-3">
												<?php echo $entry_id_card; ?>
												<span class="help">
												<?php echo $help_id_card; ?>
												</span>
											</label>
											<div class="col-sm-9">
													<div class="btn-group btn-group-toggle" data-toggle="buttons">
															<label class="btn btn-success <?php if ($hpwd_reseller_management_id_card_entry == 0) { ?> active <?php } ?>">
																<input type="radio"
																	name="hpwd_reseller_management_id_card_entry"
																	id="option1"
																	autocomplete="off" <?php if ($hpwd_reseller_management_id_card_entry == 0) { ?> checked <?php } ?>
																	value="0"> <?php echo $text_hide; ?>
															</label>
															<label class="btn btn-success <?php if ($hpwd_reseller_management_id_card_entry == 1) { ?> active <?php } ?>">
																<input type="radio"
																	name="hpwd_reseller_management_id_card_entry"
																	id="option1"
																	autocomplete="off" <?php if ($hpwd_reseller_management_id_card_entry == 1) { ?> checked <?php } ?>
																	value="1"> <?php echo $text_show; ?>
															</label>
															<label class="btn btn-success <?php if ($hpwd_reseller_management_id_card_entry == 2) { ?> active <?php } ?>">
																<input type="radio"
																	name="hpwd_reseller_management_id_card_entry"
																	id="option1"
																	autocomplete="off" <?php if ($hpwd_reseller_management_id_card_entry == 2) { ?> checked <?php } ?>
																	value="2"> <?php echo $text_required; ?>
															</label>
														</div>
											</div>
										</div>

										<div class="form-group required">
											<label class="col-sm-3" for="input-lastname">
												<?php echo $entry_instan_msg; ?>
												<span class="help"><?php echo $entry_instan_msg_sub; ?></span>
											</label>
											<div class="col-sm-9">
												<input id="check-socmed" <?php echo $hpwd_reseller_management_toggle_socmed=="1" ? 'checked' : ''; ?>
													name="hpwd_reseller_management_toggle_socmed" type="checkbox"
													data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
													data-on-label="<?php echo $text_yes; ?>">
											</div>
										</div>
									</div>
									<div class="social_media_div <?php echo $hpwd_reseller_management_toggle_socmed == "1" ? '':'hide'; ?>">
									
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-sm-3"
													for="input-status"><?php echo $entry_instan_msg_entry_whatsapp; ?></label>
												<div class="col-sm-2" style="width:120px">
													<input id="inputwhatsapp"
														onclick="return changeOption(this)" <?php echo $hpwd_reseller_management_check_entry_whatsapp ? 'checked' : ''; ?>
														name="hpwd_reseller_management_check_entry_whatsapp"
														type="checkbox" data-control="checkbox" value="1"
														data-off-label="<?php echo $text_no; ?>"
														data-on-label="<?php echo $text_yes; ?>">
												</div>
												<div class="col-sm-7">
													<div class="col-sm-1" style="padding: 0;">
														<span class="marker-down"></span>
														<select data-live-search="true"
																data-selected="<?php echo $hpwd_reseller_management_whatsapp_country; ?>"
																name="hpwd_reseller_management_whatsapp_country"
																class="country_code">
														</select>
													</div>
													<div class="col-sm-5">
														<input value="<?php echo $hpwd_reseller_management_check_entry_whatsapp ? $hpwd_reseller_management_whatsapp : ''; ?>" <?php echo $hpwd_reseller_management_check_entry_whatsapp ? '' : 'readonly'; ?>
															id="truewhatsapp" onkeyup="return changeTrue(this)"
															data-id="inputwhatsapp"
															name="hpwd_reseller_management_whatsapp"
															type="text" class="form-control"
															aria-describedby="basic-addon1">
													</div>
												</div>
											</div>
										</div>
										<?php $i = 0; ?>
										<?php foreach ($social_media as $socmed) { ?>
											<div class="col-md-12">
												<div class="form-group required">
													<label class="col-sm-3" for="input-ktp">
														<?php echo 'entry_instan_msg_entry_'. $socmed; ?>
													</label>
													<div class="col-sm-2" style="width:120px">
														<input id="input<?php echo $socmed; ?>"
															onclick="return changeOption(this)" <?php echo 'hpwd_reseller_management_check_entry_'. $socmed ? 'checked' : ''; ?>
															name="hpwd_reseller_management_check_entry_<?php echo $socmed; ?>"
															type="checkbox" data-control="checkbox" value="1"
															data-off-label="<?php echo $text_no; ?>"
															data-on-label="<?php echo $text_yes; ?>">
													</div>
													<div class="col-sm-7">
														<div class="input-group">
															<span style="width:110px"
																class="input-group-addon"><?php echo $social_ico[$i]; ?></span>
															<input id="true<?php echo $socmed; ?>" onkeyup="return changeTrue(this)"
																data-id="input<?php echo $socmed; ?>" type="text"
																name="hpwd_reseller_management_<?php echo $socmed; ?>"
																value="<?php echo 'hpwd_reseller_management_check_entry_'. $socmed ? 'hpwd_reseller_management_'. $socmed : ''; ?>" <?php echo 'hpwd_reseller_management_check_entry_'.$socmed ? '' : 'readonly'; ?>
																id="input-hpwd_reseller_management_<?php echo $socmed; ?>"
																class="form-control"/>
														</div>
													</div>
												</div>
											</div>
											<?php $i = $i + 1; ?>
										<?php } ?>
									</div>



									<div class="col-md-12">

										<div class="form-group required">
											<label class="col-sm-3" for="status">
												<?php echo $entry_social_media_profile; ?>
												<span class="help"><?php echo $help_social_media_profile; ?></span>
											</label>
											<div class="col-sm-2">
												<input name="hpwd_reseller_management_social_media_profile" type="checkbox"
													data-control="checkbox" value="1" data-off-label="<?php echo $text_no; ?>"
													data-on-label="<?php echo $text_yes; ?>" <?php if ($hpwd_reseller_management_social_media_profile) { ?> checked="checked" <?php } ?>>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 "><?php echo $entry_country; ?>
												<span class="help">
											<?php echo $help_country; ?>
										</span>
											</label>
											<div class="col-sm-9">
												<div class="col-sm-7">
													<div class="button-country">
														<a class="select-all">Select All</a>
														<a class="unselect-all">Unselect All</a>
													</div>

													<select class="form-control" name="hpwd_reseller_management_country[]"
															id="country" multiple>
													</select>
												</div>


												<?php if ($error_country) { ?>
													<div class="text-danger col-sm-12"
														style="clear: both;"><?php echo $error_country; ?></div>
												<?php } ?>

											</div>

										</div>
									</div>
								</div>
								<div class="text-right">
									<button type="submit" data-toggle="tooltip" title="<?php echo $tooltip_btn_save; ?>"
											class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $text_btn_save; ?></button>
								</div>
							</div>
							<div class="tab-pane" id="tab-group">
								<div class="alert alert-info" role="alert">
									<?php echo $help_reseller_level_tab; ?>
								</div>
								<div class="table-responsive">
									<table id="table-levels" class="table table-striped table-bordered table-hover">
										<thead>
										<tr>
											<td class="text-left"><?php echo $entry_reseller_group; ?></td>
											<td class="text-left"><?php echo $entry_level; ?></td>
											<td class="text-left"><?php echo $entry_total_checkout; ?></td>
										</tr>
										</thead>
										<tbody>
										<?php foreach ($customer_groups as $customer_group): ?>
											<?php if (in_array($customer_group['customer_group_id'], $hpwd_reseller_management_group)): ?>
												<tr id="level-row<?php echo $customer_group['customer_group_id']; ?>"
													data-level="<?php echo $hpwd_reseller_management_reseller_level[$customer_group['customer_group_id']]['level']; ?>"
													name="hpwd_reseller_management_reseller_level[<?php echo $customer_group['customer_group_id']; ?>][level]">
													<td>
														<?php echo $customer_group['name']; ?>
													</td>
													<td>
														<input value="<?php echo $hpwd_reseller_management_reseller_level[$customer_group['customer_group_id']]['level']; ?>"
															name="hpwd_reseller_management_reseller_level[<?php echo $customer_group['customer_group_id']; ?>][level]"
															type="number" class="form-control">
													</td>
													<td>
														<input id="amount<?php echo $customer_group['customer_group_id']; ?>"
															onkeyup="autoFormat('amount<?php echo $customer_group['customer_group_id']; ?>')"
															value="<?php echo $hpwd_reseller_management_reseller_level[$customer_group['customer_group_id']]['total_checkout']; ?>"
															name="hpwd_reseller_management_reseller_level[<?php echo $customer_group['customer_group_id']; ?>][total_checkout]"
															type="text" class="form-control currency-input">
													</td>
												</tr>
											<?php endif; ?>
										<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="tab-translation">
								<div class="row">
									<?php foreach ($translation as $tr) { ?>
									<?php if (isset($tr['group']) &&  $tr['group']) { ?> <div class="col-md-12"> <legend><?php echo $tr['group']; ?></legend> </div><?php } ?>
										<div class="col-md-12">
											<div class="form-group required">
												<label class="col-sm-3" for="input-lastname">
													<?php echo $tr['title']; ?>
													<span class="help"><?php echo $tr['title_sub']; ?></span>
												</label>
												<div class="col-sm-9">
													<?php foreach ($languages as $language) { ?>
														<?php if ($tr['type'] == 'input') { ?>
															<div class="input-group"><span class="input-group-addon"><img
																			src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png"
																			title="<?php echo $language['name']; ?>"/></span>
																<input type="text"
																	name="hpwd_reseller_management_<?php echo $tr['code']; ?>_<?php echo $language['language_id']; ?>"
																	value="<?php echo 'hpwd_reseller_management_'. $tr['code']. '_'. $language['language_id']; ?>"
																	class="form-control"/>
															</div>
														<?php } else { ?>
															<div class="input-group"><span class="input-group-addon"><img
																			src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png"
																			title="<?php echo $language['name']; ?>"/></span>
																<textarea style="height:100px;"
																		name="hpwd_reseller_management_<?php echo $tr['code']; ?>_<?php echo $language['language_id']; ?>"
																		class="form-control"><?php echo 'hpwd_reseller_management_'. $tr['code'].'_'. $language['language_id']; ?></textarea>
															</div>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div>

									<?php } ?>
								</div>
								<div class="text-right">
									<button type="submit" data-toggle="tooltip" title="<?php echo $tooltip_btn_save; ?>"
											class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $text_btn_save; ?></button>
								</div>
							</div>
							<div class="tab-pane" id="tab-sms">
								<fieldset>
									<legend><?php echo $text_sms_setting; ?></legend>
									<h6><?php echo $text_zenziva; ?></h6>
									<div class="form-group required">
										<label class="col-sm-3" for="input-userkey">
											<?php echo $entry_sms_gateway; ?>
										</label>
										<div class="col-sm-9">
											<select class="form-control" name="hpwd_reseller_management_sms_gateway">
												<option <?php if ($hpwd_reseller_management_sms_gateway == "wavecell") { ?> selected <?php } ?>
														value="wavecell">Wavecell (International)
												</option>
												<option <?php if ($hpwd_reseller_management_sms_gateway == "zenviva") { ?> selected <?php } ?>
														value="zenviva">Zenviva (Indonesia)
												</option>
												<option <?php if ($hpwd_reseller_management_sms_gateway == "netgsm") { ?> selected <?php } ?>
														value="netgsm">NetGSM (Turkey)
												</option>
											</select>
										</div>
									</div>
									<div class="form-group required">
										<label class="col-sm-3" for="input-userkey">
											<?php echo $entry_sms_userkey; ?>
										</label>
										<div class="input-group col-sm-5" style="padding-left: 15px;"><span
													class="input-group-addon"><i class="fa fa-user"></i></span>
											<input type="text" name="hpwd_reseller_management_sms_userkey"
												value="<?php echo $hpwd_reseller_management_sms_userkey; ?>" id="input-userkey"
												class="form-control"/>
										</div>
									</div>
									<div class="form-group required">
										<label class="col-sm-3" for="input-passkey">
											<?php echo $entry_sms_passkey; ?>
										</label>
										<div class="input-group col-sm-5" style="padding-left: 15px;"><span
													class="input-group-addon"><i class="fa fa-key"></i></span>
											<input type="password" name="hpwd_reseller_management_sms_passkey"
												value="<?php echo $hpwd_reseller_management_sms_passkey; ?>" id="input-passkey"
												class="form-control"/>
										</div>
									</div>
									<legend><?php echo $text_sms_option; ?></legend>
									<div class="form-group required">
										<label class="col-sm-3">
											<?php echo $entry_sms_receipt; ?>
										</label>
										<div class="col-sm-9">
											<input name="hpwd_reseller_management_sms_send_receipt" id="check-sms"
												value="1" <?php if ($hpwd_reseller_management_sms_send_receipt==1) { ?> checked='checked' <?php } ?>
												type="checkbox" data-control="checkbox" data-off-label="<?php echo $text_no; ?>"
												data-on-label="<?php echo $text_yes; ?>">
										</div>
									</div>
									<div class="form-group required group-sms <?php echo $hpwd_reseller_management_sms_send_receipt==1 ? '' : 'hide'; ?>">
										<label class="col-sm-3" for="input-receipt-template">
											<?php echo $entry_sms_receipt_template; ?>
										</label>
										<div class="col-sm-9">
											<textarea name="hpwd_reseller_management_sms_receipt_template" rows="8"
													class="form-control"
													id="input-receipt-template"><?php echo $hpwd_reseller_management_sms_receipt_template; ?></textarea>
										</div>
									</div>
								</fieldset>
							</div>
							<div class="tab-pane" id="tab-advance">
								<div class="form-group">
									<label class="col-sm-3">
										<?php echo $text_uninstal_table; ?>
										<h6><?php echo $text_uninstal_table_sub; ?></h6>
									</label>
									<div class="col-sm-9">
										<span onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $uninstall; ?>' : false;"
											class="btn btn-danger"><i
													class="fa fa-trash"></i> <?php echo $text_uninstal_table; ?></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3">
										<?php echo $entry_patch_upgrade; ?>
										<h6><?php echo $help_patch_upgrade; ?></h6>
									</label>
									<div class="col-sm-9">
										<span onclick="location.href='<?php echo $upgrade; ?>'"
											class="btn btn-primary"><i class="fa fa-database"
																		aria-hidden="true"></i> <?php echo $text_upgrade_database; ?></span>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab-help">
								<div id="license"></div>
								<div id="support"></div>
								<script type="text/javascript">
									$('#support').load('index.php?route=common/hp_validate/support&token=<?php echo $token; ?>');
									$('#license').load('index.php?route=common/hp_validate/license&token=<?php echo $token; ?>&code=<?php echo $extension_code; ?> }}&version=<?php echo $version ?>}}');
								</script>
							</div>
						</div>
                    </div> <!-- .main-content ends -->

                </div> <!-- panel-right ends -->

            </div>
        </form>
    </div> <!-- content ends -->
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

	.select-all, .unselect-all {
		cursor: pointer;

	}

	.button-country {
		margin-bottom: 10px;
	}

	.select-all {
		margin-right: 10px;
	}

	.marker-down {
		width: 0px;
		height: 0px;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		border-top: 5px solid #2f2f2f;
		position: absolute;
		z-index: 999;
		right: -7px;
		top: 16px;
	}
</style>
<script type="text/javascript">
	const country = <?php  echo $hpwd_reseller_management_country; ?>;
	const availabel_countries = <?php echo $availabel_countries ?>;
	const allcountry = <?php echo json_encode($countries); ?>;
	let selected_country = [];


	$(document).ready(function () {
		// const extension_type1 = <?php echo $extension_type; ?>;
		// const text_upgrade_version1 = <?php echo $text_upgrade_version ?>;
		// const version1 = <?php echo $version; ?>
		// $.get(`https://hpwebdesign.${extension_type1}/index.php?route=common/extension/version&code=hprm&oc=3.0.x.x`, function(data, status){
		// const version =  `${version1}`.replace(/\D/g,'');
		// const latest_version = data.replace(/\D/g,'');
	//  if(version<latest_version){
	// 	 $('.panel.panel-default').parent().prepend(`<div class="alert alert-danger">
	// 	 <i class="fa fa-exclamation-circle"></i>
	// 	 ${text_upgrade_version1}
	// 	 </div>`);
	//  }
//  });

		fetch("view/javascript/selectpicker/js/countryPhone.json").then(res => res.json()).then(json => {
			$(".country_code").each(function () {
				let defaultValue = $(this).attr('data-selected');
				let options = [];
				json.forEach(country => {


					$('#country').append(`<option value='${country.name}--${country.code}--${country.phone}' >${country.name} (+${country.phone})</option>`);
					if (checkedPhoneCountry(`${country.name}--${country.code}--${country.phone}`)) {
						selected_country.push(`${country.name}--${country.code}--${country.phone}`);
					}

					if (defaultValue == country.phone) {
						options.push(
							"<option selected title='+" + country.phone + "' value='" + country.phone + "' >" + country.name + " +" + country.phone + "</option>"
						);
					} else {
						options.push(
							"<option title='+" + country.phone + "' value='" + country.phone + "' >" + country.name + " +" + country.phone + "</option>"
						);
					}
				})
				$(this).html(options);
				$(this).selectpicker();
				$('#country').select2({width: '100%'}).val(selected_country).trigger('change');
			})
		});


		$(".select-all").click(function (e) {
			e.preventDefault();
			$('#country option').prop('selected', 'selected').parent().trigger('change');
		});

		$(".unselect-all").click(function (e) {
			e.preventDefault();
			$('#country option').prop('selected', false).parent().trigger('change');
		});


	});

	function checkedPhoneCountry(phonecode) {
		let result = false;
		country.forEach(function (item, k) {
			if (item == phonecode) {
				result = true;
			}
		});

		return result;
	}
</script>
<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {
			if ($.fn.checkboxpicker) {
				$('[data-control=checkbox]').checkboxpicker({onClass: 'btn-info'});
				$('#check-socmed').checkboxpicker({onClass: 'btn-info'});
			}
			$('.btn-group .btn').addClass('btn-sm');
		});
	})(jQuery);


	$('[data-control=checkbox]').on('change', function () {
		var ch = $(this).prop('checked')
		var parent = $(this).attr('id')
		if (ch == true) {
			$('input[data-id="' + parent + '"]').removeAttr('readonly');
		} else {
			$('input[data-id="' + parent + '"]').attr('readonly', 'readonly');
		}
	})
	$('#check-socmed').on('change', function () {
		var ch = $(this).prop('checked')

		if (ch == true) {
			$('.social_media_div').removeClass('hide');
		} else {
			$('.social_media_div').addClass('hide');
		}
	})
	$('#check-sms').on('change', function () {
		var ch = $(this).prop('checked')

		if (ch == true) {
			$('.group-sms').removeClass('hide');
		} else {
			$('.group-sms').addClass('hide');
		}
	})
</script>

<script>

	$(document).ready(() => {
		sortLevel();
		$(".currency-input").each((key, el) => {
			formatCurrency($(el));
		})
	})

	function sortLevel() {
		let rows = $("#table-levels tbody tr");
		let rowsLevel = [];

		rows.each((key, row) => {
			rowsLevel.push({
				level: $(row).data().level,
				html: row
			});
		})

		rowsLevel.sort((a, b) => {
			if (a.level < b.level) {
				return -1
			}

			if (a.level > b.level) {
				return 1
			}

			return 0
		})

		$("#table-levels tbody").html('')

		rowsLevel.forEach((row) => {
			$("#table-levels tbody").append(row.html);
		})
	}

	function addLevelRow(data) {
		let html = "";

		html = `
			<tr id="level-row${data['customer_group_id']}" data-level="">
				<td>
					${data['name']}
				</td>
				<td>
					<input name="hpwd_reseller_management_reseller_level[${data['customer_group_id']}][level]" type="number" class="form-control">
				</td>
				<td>
					<input id="amount${data['customer_group_id']}" onkeyup="autoFormat('amount${data['customer_group_id']}')" name="hpwd_reseller_management_reseller_level[${data['customer_group_id']}][total_checkout]" type="text" class="form-control currency-input">
				</td>
			</tr>
		`;

		$("#table-levels tbody").append(html);
	}

	$(".customer-group-checkbox").change((el) => {
		let checkbox = $(el.target);
		let data = checkbox.data().customer;
		let isChecked = checkbox.prop("checked");

		if (isChecked) {
			addLevelRow(data);
		} else {
			$(`#level-row${data['customer_group_id']}`).remove();
		}
	})

	$("#form-setting").submit(() => {

		$(".currency-input").each((key, el) => {
			let input = $(el);
			let value = input.val();

			input.val(Number(value.replace(/[^0-9-]+/g, "")));
		})

		return true;
	})

	let currency_symbol = "<?php echo $currency_symbol; ?>";
	let thousand_point = "<?php echo $thousand_point; ?>";

	function autoFormat(e) {
		formatCurrency($("#" + e));
	}

	function formatNumber(n) {
		// format number 1000000 to 1,234,567
		return n['replace'](/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, thousand_point)
	}

	function formatCurrency(input, blur) {
		// appends $ to value, validates decimal side
		// && $puts curs|| $back in right position.

		// get input value
		let input_val = input.val();

		// don't validate empty input
		if (input_val === "") {
			return;
		}

		// original length
		let original_len = input_val.length;

		// initial caret position
		let caret_pos = input.prop("selectionStart");


		// no decimal entered
		// add commas to number
		// remove all non-digits
		input_val = formatNumber(input_val);
		input_val = currency_symbol + input_val;
		// final formatting
		if (blur === "blur") {
			input_val += ".00";
		}


		// send updated string to input
		input.val(input_val);

		// put caret back in the right position
		let updated_len = input_val.length;
		caret_pos = updated_len - original_len + caret_pos;
		input[0].setSelectionRange(caret_pos, caret_pos);
	}
</script>

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<?php echo $footer; ?>
