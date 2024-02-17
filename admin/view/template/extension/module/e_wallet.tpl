<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
         <div class="dropdown pull-left">
           <button class="btn btn-default dropdown-toggle" style="margin-right:5px;" type="button" id="dropdownMenuButton" data-toggle="dropdown">
           Other Pages
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
             <span class="input-group-btn" id="pad-auto">
                  <a class="btn pull-left" target="_blank" href="<?php echo $voucher; ?>"><?php echo $text_redeem_voucher; ?></a>
                  <a class="btn pull-left" target="_blank" href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a>&nbsp;
                  <a class="btn pull-left" target="_blank" href="<?php echo $add_money_request; ?>"><?php echo $text_add_money_req; ?></a>&nbsp;
                  <a class="btn pull-left" target="_blank" href="<?php echo $customer_balance;?>"><?php echo $text_customer; ?></a>
            </span>   
          </div>
        </div>
        <button type="submit" form="form-e_wallet" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <h1><?php echo $heading_title; ?></h1>
        <ul class="breadcrumb">
          <?php foreach($breadcrumbs as $_key => $breadcrumb) { ?> 
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
      <?php if(isset($error_warning)) { ?>
      <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
       <?php if($success) { ?>
        <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
       <?php } ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
        </div>
        <div class="panel-body">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-e_wallet">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
              <li><a href="#tab-language" data-toggle="tab"><?php echo $tab_language; ?></a></li>
              <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
              <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>  
              <li><a href="#tab-bank" data-toggle="tab"><?php echo $tab_bank; ?></a></li>          
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab-general">
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-status"><?php echo $entry_status; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_status" id="input-status" class="form-control">
                          <?php if (isset($e_wallet_status)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-delete_all_data"><?php echo $entry_delete_all_data; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_delete_on_uninstall" id="input-delete_all_data" class="form-control">
                          <?php if(isset($e_wallet_delete_on_uninstall)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-delete_voucher_order"><?php echo $entry_delete_voucher_order; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_delete_voucher_order" id="input-delete_voucher_order" class="form-control">
                          <?php if (isset($e_wallet_delete_voucher_order)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-e_wallet_add_money"><?php echo $add_money; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_add_money" id="input-e_wallet_add_money" class="form-control">
                          <?php if (isset($e_wallet_add_money)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-e_wallet_send_money"><?php echo $send_money; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_send_money" id="input-e_wallet_send_money" class="form-control">
                          <?php if (isset($e_wallet_send_money)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-e_wallet_bank_detail"><?php echo $bank_detail; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_bank_detail" id="input-e_wallet_bank_detail" class="form-control">
                          <?php if (isset($e_wallet_bank_detail)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-e_wallet_withdraw_requests"><?php echo $withdraw_requests; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_withdraw_requests" id="input-e_wallet_withdraw_requests" class="form-control">
                          <?php if (isset($e_wallet_withdraw_requests)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-e_wallet_voucher_status"><?php echo $voucher_status; ?></label>
                      <div class="col-xs-12">
                        <select name="e_wallet_voucher_status" id="input-e_wallet_voucher_status" class="form-control">
                          <?php if (isset($e_wallet_voucher_status)) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-setting-mail"><?php echo $entry_transaction_admin_email; ?></label>
                      <div class="col-xs-12">
                        <input type="text" name="e_wallet_setting_mail" value="<?php echo $e_wallet_setting_mail; ?>" placeholder="<?php echo $entry_transaction_admin_email; ?>" id="input_setting_mail" class="form-control">
                         <?php if(isset($error_setting_mail)) { ?>
                          <div class="text-danger"><?php echo $error_setting_mail; ?></div>
                         <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab-language">
                <ul class="nav nav-tabs" id="language">
                  <?php $active_is = 1; ?>
 
                  <?php $active_class = ''; ?>
 
                  <?php foreach($languages as $_key => $language) { ?> 
                  <?php $active_class = (($active_is == 1) ? ('active') : ('')); ?>
                  <li class="<?php echo $active_class; ?>">
                    <a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"> <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>"/> <?php echo $language['name']; ?> </a>
                  </li>
                  <?php $active_is = ($active_is + 1); ?>
                  <?php } ?>
                </ul>
                <div class="tab-content">
                  <?php $active_is = 1; ?>
 
                  <?php $active_class = ''; ?>
 
                  <?php foreach($languages as $_key => $language) { ?> 
                  <?php $active_class = (($active_is == 1) ? ('active') : ('')); ?>
                  <div class="tab-pane <?php echo $active_class; ?>" id="language<?php echo $language['language_id']; ?>">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group required">
                        <label class="col-xs-12 control-label" for="input-e_title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                        <div class="col-xs-12">
                          <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][title]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['title']) : ('')); ?>" placeholder="<?php echo $entry_title; ?>" id="input-e_title" class="form-control" />
                          <?php if (isset($error_title[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group required">
                        <label class="col-xs-12 control-label" for="input-acc_title<?php echo $language['language_id']; ?>"><?php echo $entry_e_account_title; ?></label>
                        <div class="col-xs-12">
                          <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][e_account_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['e_account_text']) : ('')); ?>" placeholder="<?php echo $entry_e_account_title; ?>" id="input-acc_title" class="form-control" />
                          <?php if (isset($error_e_account_text[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_e_account_text[$language['language_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                   </div>
                   <div class="row">
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-add-money-title<?php echo $language['language_id']; ?>"><?php echo $entry_add_money_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][add_money_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_money_text']) : ('')); ?>" placeholder="<?php echo $entry_add_money_title; ?>" id="input-add-money-title" class="form-control" />
                            <?php if (isset($error_add_money_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_add_money_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-send-money-title<?php echo $language['language_id']; ?>"><?php echo $entry_send_money_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][send_money_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['send_money_text']) : ('')); ?>" placeholder="<?php echo $entry_send_money_title; ?>" id="input-send-money-title" class="form-control" />
                            <?php if (isset($error_send_money_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_send_money_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-withdraw-money-title<?php echo $language['language_id']; ?>"><?php echo $entry_withdraw_money_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][withdraw_money_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['withdraw_money_text']) : ('')); ?>" placeholder="<?php echo $entry_withdraw_money_title; ?>" id="input-withdraw-money-title" class="form-control" />
                            <?php if (isset($error_withdraw_money_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_withdraw_money_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-voucher-title<?php echo $language['language_id']; ?>"><?php echo $entry_voucher_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][voucher_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['voucher_text']) : ('')); ?>" placeholder="<?php echo $entry_voucher_title; ?>" id="input-voucher-title" class="form-control" />
                            <?php if (isset($error_voucher_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_voucher_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-bank-title<?php echo $language['language_id']; ?>"><?php echo $entry_bank_detail_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][bank_detail_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['bank_detail_text']) : ('')); ?>" placeholder="<?php echo $entry_bank_detail_title; ?>" id="input-bank-detail-title" class="form-control" />
                            <?php if (isset($error_bank_detail_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_bank_detail_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-payment_method_title<?php echo $language['language_id']; ?>"><?php echo $entry__wallet_payment_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][payment_method_title]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['payment_method_title']) : ('')); ?>" placeholder="<?php echo $entry__wallet_payment_title; ?>" id="input-payment_method_title<?php echo $language['language_id']; ?>" class="form-control"/>
                            <?php if (isset($error_payment_method_title[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_payment_method_title[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-total_title<?php echo $language['language_id']; ?>"><?php echo $entry_total_title; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][total_title]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['total_title']) : ('')); ?>" placeholder="<?php echo $entry_total_title; ?>" id="input-total_title<?php echo $language['language_id']; ?>" class="form-control" />
                            <?php if (isset($error_total_title[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_total_title[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label class="col-xs-12 control-label" for="input-half_payment_line<?php echo $language['language_id']; ?>">
                            <?php echo $entry_half_payment_line; ?>
                          </label>
                          <div class="col-xs-12">
                            <textarea class="note-editable" name="e_wallet_language[<?php echo $language['language_id']; ?>][half_payment_line]" data-toggle="summernote" data-lang="<?php echo $summernote; ?>"  placeholder="<?php echo $entry_half_payment_line; ?>" id="input-half_payment_line<?php echo $language['language_id']; ?>"><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['half_payment_line']) : ('')); ?></textarea>
                            <?php if (isset($error_half_payment_line[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_half_payment_line[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <span class="top_text"><?php echo $entry_note_text; ?>:</span>
                              <ul>
                                <li><?php echo $entry_order_total; ?></li>
                                <li><?php echo $entry_wallet_total; ?></li>
                                <li><?php echo $entry_remain_chat; ?></li>
                              </ul>
                              <span class="example"><?php echo $entry_example_text; ?></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label class="col-xs-12 control-label" for="input-full_payment_line<?php echo $language['language_id']; ?>">
                            <?php echo $entry_full_payment_line; ?>
                          </label>
                          <div class="col-xs-12">
                            <textarea class="note-editable" name="e_wallet_language[<?php echo $language['language_id']; ?>][full_payment_line]"  data-toggle="summernote" data-lang="<?php echo $summernote; ?>"  placeholder="<?php echo $entry_full_payment_line; ?>" id="input-full_payment_line<?php echo $language['language_id']; ?>"><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['full_payment_line']) : ('')); ?></textarea>
                            <?php if (isset($error_full_payment_line[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_full_payment_line[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <span class="example"><?php echo $entry_sufficent_msg; ?></span>
                            </span>
                          </div>
                        </div>
                      </div>
                   </div>
                   <div class="row">
                     <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label class="col-xs-12 control-label" for="input-email_header_text<?php echo $language['language_id']; ?>">
                            <?php echo $entry_header_title; ?>
                          </label>
                          <div class="col-xs-12">
                            <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][email_header_text]" rows="3" cols="75" class="form-control" placeholder="<?php echo $entry_header_title; ?>" id="input-email_header_text<?php echo $language['language_id']; ?>"><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['email_header_text']) : ('')); ?></textarea>
                            <?php if (isset($error_email_header_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_email_header_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <span class="example"><?php echo $entry_header_text_note; ?></span>
                            </span>
                          </div>
                        </div>
                     </div>
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label class="col-xs-12 control-label" for="input-email_footer_text<?php echo $language['language_id']; ?>">
                            <?php echo $entry_footer_title; ?>
                          </label>
                          <div class="col-xs-12">
                            <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][email_footer_text]" rows="3" cols="75" class="form-control" placeholder="<?php echo $entry_footer_title; ?>" id="input-email_footer_text<?php echo $language['language_id']; ?>"><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['email_footer_text']) : ('')); ?></textarea>
                            <?php if (isset($error_email_footer_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_email_footer_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <span class="example"><?php echo $entry_footer_text_note; ?></span>
                            </span>
                          </div>
                        </div>
                     </div>
                   </div>
                   <div class="row">
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-voucher-title<?php echo $language['language_id']; ?>"><?php echo $entry_add_confirm_msg; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][add_confirm_string_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_confirm_string_text']) : ('')); ?>" placeholder="<?php echo $entry_add_confirm_msg; ?>" id="input-voucher-title" class="form-control" />
                            <?php if (isset($error_add_confirm_string_text[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_add_confirm_string_text[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <ul>
                                <li><?php echo $entry_add_money_text_string; ?></li>
                                <li><?php echo $entry_wallet_text; ?></li>
                              </ul>
                              <span class="example"><?php echo $entry_add_confirm_example_text; ?></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-sufficient-msg-title<?php echo $language['language_id']; ?>"><?php echo $entry_sufficient_msg; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][sufficient_msg]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['sufficient_msg']) : ('')); ?>" placeholder="<?php echo $entry_sufficient_msg; ?>" id="input-send-money-title-text" class="form-control" />
                            <?php if (isset($error_sufficient_msg[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_sufficient_msg[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <ul>
                                <li><?php echo $entry_wallet_text; ?></li>
                              </ul>
                              <span class="example"><?php echo $entry_sufficient_msg_note; ?></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                          <div class="form-group required">
                            <label class="col-xs-12 control-label" for="input-sufficient-msg-title<?php echo $language['language_id']; ?>"><?php echo $entry_invalid_method; ?></label>
                            <div class="col-xs-12">
                              <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][invalid_method]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['invalid_method']) : ('')); ?>" placeholder="<?php echo $entry_invalid_method; ?>" id="input-invalid-method-text" class="form-control" />
                              <?php if (isset($error_invalid_method[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_invalid_method[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                            <div class="col-xs-12">
                              <span class="example_note">
                                <span class="example"><?php echo $entry_invalid_paymnet_method_note; ?></span>
                              </span>
                            </div>
                          </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group required">
                          <label class="col-xs-12 control-label" for="input-success-msg-title<?php echo $language['language_id']; ?>"><?php echo $entry_bank_success_msg; ?></label>
                          <div class="col-xs-12">
                            <input type="text" name="module_e_wallet_language[<?php echo $language['language_id']; ?>][bank_success_msg]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['bank_success_msg']) : ('')); ?>" placeholder="<?php echo $entry_invalid_method; ?>" id="input-success-method-text" class="form-control" />
                            <?php if(isset($error_bank_success_msg[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_bank_success_msg[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                          <div class="col-xs-12">
                            <span class="example_note">
                              <ul>
                                <li><?php echo $entry_wallet_text; ?></li>
                              </ul>
                              <span class="example"><?php echo $entry_bank_success_msg_note; ?></span>
                            </span>
                          </div>
                        </div>
                     </div>
                   </div>
                   <fieldset class="well well-sm">
                    <legend class="mb0"><?php echo $entry_string_data_details; ?></legend>
                      <div class="row">
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-money-string-title<?php echo $language['language_id']; ?>"><?php echo $entry_add_money_string_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][add_money_string_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_money_string_text']) : ('')); ?>" placeholder="<?php echo $entry_add_money_title; ?>" id="input-add-money-string-title" class="form-control" />
                                  <?php if (isset($error_add_money_string_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_money_string_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_add_money_text_string; ?></li>
                                      <li><?php echo $entry_wallet_text; ?></li>
                                     <!--  <li><?php echo $entry_amount_text; ?></li> -->
                                    </ul>
                                    <pre class="example"><?php echo $entry_add_example_text; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-send-money-string-title<?php echo $language['language_id']; ?>"><?php echo $entry_send_money_string_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][send_money_string_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['send_money_string_text']) : ('')); ?>" placeholder="<?php echo $entry_send_money_string_text; ?>" id="input-send-money-string-title" class="form-control" />
                                  <?php if (isset($error_send_money_string_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_send_money_string_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_send_text; ?></li>
                                      <li><?php echo $entry_name_text; ?></li>
                                      <li><?php echo $entry_email_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_send_example_text; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-withdraw-money-string-title<?php echo $language['language_id']; ?>"><?php echo $entry_withdraw_money_string_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][withdraw_string_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['withdraw_string_text']) : ('')); ?>" placeholder="<?php echo $entry_withdraw_money_string_text; ?>" id="input-withdraw-money-string-title" class="form-control" />
                                  <?php if (isset($error_withdraw_string_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_withdraw_string_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_withdraw_data_text; ?></li>
                                      <li><?php echo $entry_e_title; ?></li>
                                      <li><?php echo $entry_balance_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_withdraw_example_text; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-paid-order-id-text<?php echo $language['language_id']; ?>"><?php echo $entry_paid_orderid_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][order_id_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['order_id_text']) : ('')); ?>" placeholder="<?php echo $entry_paid_orderid_text; ?>" id="input-paid-order-id-text" class="form-control" />
                                  <?php if (isset($error_order_id_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_order_id_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_order_id; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_orderid_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-refund-string-title<?php echo $language['language_id']; ?>"><?php echo $entry_refund_amount_string_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][refund_string_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['refund_string_text']) : ('')); ?>" placeholder="<?php echo $entry_refund_amount_string_text; ?>" id="input-refund-string-title" class="form-control" />
                                  <?php if (isset($error_refund_string_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_refund_string_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_order_id; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_refund_orderid_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-approve-text<?php echo $language['language_id']; ?>"><?php echo $entry_request_add_money_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][approve_request_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['approve_request_text']) : ('')); ?>" placeholder="<?php echo $entry_request_add_money_text; ?>" id="input-approve-text" class="form-control" />
                                  <?php if (isset($error_approve_request_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_approve_request_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_add_money_text_string; ?></li>
                                      <li><?php echo $entry_amount_text; ?></li>
                                      <li><?php echo $entry_method_title; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_request_approved_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-receive-money-string-text<?php echo $language['language_id']; ?>"><?php echo $entry_receive_money_string_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][receive_money_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['receive_money_text']) : ('')); ?>" placeholder="<?php echo $entry_receive_money_string_text; ?>" id="input-receive-money-string-text" class="form-control" />
                                  <?php if (isset($error_receive_money_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_receive_money_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_firstname_text; ?></li>
                                      <li><?php echo $entry_lastname_text; ?></li>
                                      <li><?php echo $entry_email_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_receive_money_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-req_approve_text<?php echo $language['language_id']; ?>"><?php echo $entry_request_aproove_text; ?></label>
                                <div class="col-xs-12">
                                  <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][req_aproove_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['req_aproove_text']) :''); ?>" placeholder="<?php echo $entry_request_aproove_text; ?>" id="input-req_approve_text" class="form-control" />
                                  <?php if (isset($error_req_aproove_text[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_req_aproove_text[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_description_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_request_aprove_string; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 col-sm-3">
                          <div class="row">
                            <div class="form-group required">
                              <label class="col-xs-12 control-label" for="input-req_reject_text<?php echo $language['language_id']; ?>"><?php echo $entry_request_reject_text; ?></label>
                              <div class="col-xs-12">
                                <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][req_reject_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['req_reject_text']) : ('')); ?>" placeholder="<?php echo $entry_request_reject_text; ?>" id="input-req_reject_text" class="form-control" />
                                <?php if (isset($error_req_reject_text[$language['language_id']])) { ?>
                                <div class="text-danger"><?php echo $error_req_reject_text[$language['language_id']]; ?></div>
                                <?php } ?>
                              </div>
                              <div class="col-xs-12">
                                <span class="example_note">
                                  <ul>
                                    <li><?php echo $entry_description_text; ?></li>
                                  </ul>
                                  <pre class="example"><?php echo $entry_request_reject_string; ?></pre>
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                          <div class="row">
                            <div class="form-group required">
                              <label class="col-xs-12 control-label" for="input-add_voucher_text<?php echo $language['language_id']; ?>"><?php echo $entry_add_voucher_text; ?></label>
                              <div class="col-xs-12">
                                <input type="text" name="e_wallet_language[<?php echo $language['language_id']; ?>][add_voucher_text]" value="<?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_voucher_text']) : ('')); ?>" placeholder="<?php echo $entry_add_voucher_text; ?>" id="input-add_voucher_text" class="form-control" />
                                <?php if (isset($error_add_voucher_text[$language['language_id']])) { ?>
                                <div class="text-danger"><?php echo $error_add_voucher_text[$language['language_id']]; ?></div>
                                <?php } ?>
                              </div>
                              <div class="col-xs-12">
                                <span class="example_note">
                                  <ul>
                                    <li><?php echo $enter_v_text; ?></li>
                                  </ul>
                                  <pre class="example"><?php echo $entry_voucher_string; ?></pre>
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                   </fieldset>
                   <fieldset class="well well-sm">
                    <legend class="mb0"><?php echo $entry_email_data_details; ?></legend>
                       <div class="row">
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-transaction-email-sub<?php echo $language['language_id']; ?>"><?php echo $entry_add_transaction_email_sub; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_transaction_email_sub]"rows="4" cols="30" placeholder="<?php echo $entry_add_transaction_email_sub; ?>" id="input-add-transaction-email-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_transaction_email_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_transaction_email_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_transaction_email_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_wallet_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_transaction_email_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-transaction-mail-msg<?php echo $language['language_id']; ?>"><?php echo $entry_add_transaction_email_msg; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][transaction_mail_msg]" rows="4" cols="30" placeholder="<?php echo $entry_add_transaction_email_msg; ?>" id="input-transaction-mail-msg" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['transaction_mail_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_transaction_mail_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_transaction_mail_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_description_text; ?></li>
                                      <li><?php echo $entry_balance_text; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_email_note_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-receive-money-email-sub<?php echo $language['language_id']; ?>"><?php echo $entry_receive_money_email_sub; ?></label>
                                <div class="col-xs-12">
                                  <textarea  name="e_wallet_language[<?php echo $language['language_id']; ?>][receive_money_email_sub]" rows="4" cols="30"  placeholder="<?php echo $entry_receive_money_email_sub; ?>" id="input-receive-money-email-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['receive_money_email_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_receive_money_email_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_receive_money_email_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_e_title; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_receive_money_email_note; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-receive-email-msg<?php echo $language['language_id']; ?>"><?php echo $entry_receive_email_msg; ?></label>
                                <div class="col-xs-12">
                                  <textarea type="text" rows="4" cols="30" name="e_wallet_language[<?php echo $language['language_id']; ?>][receive_email_msg]" placeholder="<?php echo $entry_receive_email_msg; ?>" id="input-receive-email-msg" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['receive_email_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_receive_email_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_receive_email_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_firstname_text; ?></li>
                                      <li><?php echo $entry_email_text; ?></li>
                                      <li><?php echo $entry_balance_text; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_receive_note; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-user-sub<?php echo $language['language_id']; ?>"><?php echo $entry_addmail_sub_user; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_user_sub]"  placeholder="<?php echo $entry_addmail_sub_user; ?>" rows="4" cols="30" id="input-add-user-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_user_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_user_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_user_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_wallet_title; ?></li>
                                      <li><?php echo $entry_add_money_text_string; ?></li>
                                      <li><?php echo $entry_status_approve; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_add_money_subject; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-user-msg<?php echo $language['language_id']; ?>"><?php echo $entry_addmail_msg_user; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_user_msg]"  placeholder="<?php echo $entry_addmail_msg_user; ?>" rows="4" cols="30" id="input-add-user-msg" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_user_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_user_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_user_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_status_approve; ?></li>
                                      <li><?php echo $entry_description_text; ?></li>
                                      <li><?php echo $entry_amount_data; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_add_money_message; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-withdraw-user-sub<?php echo $language['language_id']; ?>"><?php echo $entry_withdraw_mail_sub_user; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][withdraw_user_sub]"  placeholder="<?php echo $entry_withdraw_mail_sub_user; ?>" rows="4" cols="30" id="input-withdraw-user-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['withdraw_user_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_withdraw_user_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_withdraw_user_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_wallet_title; ?></li>
                                      <li><?php echo $entry_withdraw_title_subject; ?></li>
                                      <li><?php echo $entry_status_approve; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_withdraw_subject; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-withdraw-user-msg<?php echo $language['language_id']; ?>"><?php echo $entry_withdraw_mail_msg_user; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][withdraw_user_msg]"  placeholder="<?php echo $entry_withdraw_mail_msg_user; ?>" rows="4" cols="30" id="input-withdraw-user-msg" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['withdraw_user_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_withdraw_user_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_withdraw_user_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                       <li><?php echo $entry_status_approve; ?></li>
                                       <li><?php echo $entry_description_text; ?></li>
                                       <li><?php echo $entry_amount_data; ?></li>
                                       <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_withdraw_message; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-send-data-sub<?php echo $language['language_id']; ?>"><?php echo $entry_send_data_sub; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][send_data_sub]"  placeholder="<?php echo $entry_send_data_sub; ?>" rows="4" cols="30" id="input-send-data-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['send_data_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_send_data_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_send_data_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_email_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_send_data_note; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-send-money-msg<?php echo $language['language_id']; ?>"><?php echo $entry_send_data_msg; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][send_money_msg]" rows="4" cols="30" placeholder="<?php echo $entry_send_data_msg; ?>" id="input-send-money-msg" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['send_money_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_send_money_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_send_money_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_s_data_text; ?></li>
                                      <li><?php echo $entry_name_text; ?></li>
                                      <li><?php echo $entry_email_text; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_send_data_msg_note; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-notify-email-sub<?php echo $language['language_id']; ?>"><?php echo $entry_notify_customer_sub; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][notify_email_sub]" rows="4" cols="30" placeholder="<?php echo $entry_notify_customer_sub; ?>" id="input-notify-email-sub" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['notify_email_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_notify_email_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_notify_email_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_wallet_title; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_notify_customer_subject; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-notify-email-msg<?php echo $language['language_id']; ?>"><?php echo $entry_notify_customer_msg; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][notify_email_msg]" rows="4" cols="30" placeholder="<?php echo $entry_notify_customer_msg; ?>" id="input-notify-email-msg" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['notify_email_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_notify_email_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_notify_email_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_e_title; ?></li>
                                      <li><?php echo $entry_description_text; ?></li>
                                      <li><?php echo $entry_email_text; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_notify_customer_message; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                       </div>
                       <div class="row">
                         <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-money-req-sub<?php echo $language['language_id']; ?>"><?php echo $entry_add_money_req_sub; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_money_req_sub]"  placeholder="<?php echo $entry_add_money_req_sub; ?>" rows="4" cols="30" id="input-add-money-req-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_money_req_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_money_req_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_money_req_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <pre class="example"><?php echo $entry_add_money_request_subject; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                         </div>
                         <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-money-req-msg<?php echo $language['language_id']; ?>"><?php echo $entry_add_money_req_msg; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_money_req_msg]"  placeholder="<?php echo $entry_add_money_req_msg; ?>" rows="4" cols="30" id="input-add-money-req-msg" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_money_req_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_money_req_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_money_req_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_description_text; ?></li>
                                      <li><?php echo $entry_amount_data; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_add_money_request_message; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                         </div>
                       </div>
                   </fieldset>
                   <fieldset class="well well-sm">
                      <legend><?php echo $entry_admin_email_text; ?></legend>
                      <div class="row">
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-setting-sub<?php echo $language['language_id']; ?>"><?php echo $entry_addmail_sub_admin; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_setting_sub]"  placeholder="<?php echo $entry_addmail_sub_admin; ?>" rows="4" cols="30" id="input-add-setting-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_setting_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_setting_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_setting_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_wallet_title; ?></li>
                                      <li><?php echo $entry_add_money_text_string; ?></li>
                                      <li><?php echo $entry_status_approve; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_add_money_subject; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-add-setting-msg<?php echo $language['language_id']; ?>"><?php echo $entry_addmail_msg_admin; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][add_setting_msg]"  placeholder="<?php echo $entry_addmail_msg_admin; ?>" rows="4" cols="30" id="input-add-setting-msg" class="form-control" /><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['add_setting_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_add_setting_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_add_setting_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_add_money_text_string; ?></li>
                                      <li><?php echo $entry_status_approve; ?></li>
                                      <li><?php echo $entry_description_text; ?></li>
                                      <li><?php echo $entry_amount_data; ?></li>
                                      <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_add_money_message_setting; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-withdraw-setting-sub<?php echo $language['language_id']; ?>"><?php echo $entry_withdraw_mail_sub_admin; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][withdraw_setting_sub]" rows="4" cols="30" placeholder="<?php echo $entry_withdraw_mail_sub_admin; ?>" id="input-withdraw-setting-sub" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['withdraw_setting_sub']) : ('')); ?></textarea>
                                  <?php if (isset($error_withdraw_setting_sub[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_withdraw_setting_sub[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                      <li><?php echo $entry_wallet_title; ?></li>
                                      <li><?php echo $entry_withdraw_data_text; ?></li>
                                      <li><?php echo $entry_status_approve; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_withdraw_subject; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-3">
                            <div class="row">
                              <div class="form-group required">
                                <label class="col-xs-12 control-label" for="input-withdraw-setting-msg<?php echo $language['language_id']; ?>"><?php echo $entry_withdraw_mail_msg_admin; ?></label>
                                <div class="col-xs-12">
                                  <textarea name="e_wallet_language[<?php echo $language['language_id']; ?>][withdraw_setting_msg]" rows="4" cols="30" placeholder="<?php echo $entry_withdraw_mail_msg_admin; ?>" id="input-withdraw-setting-msg" class="form-control"/><?php echo (($e_wallet_language[$language['language_id']]) ? ($e_wallet_language[$language['language_id']]['withdraw_setting_msg']) : ('')); ?></textarea>
                                  <?php if (isset($error_withdraw_setting_msg[$language['language_id']])) { ?>
                                  <div class="text-danger"><?php echo $error_withdraw_setting_msg[$language['language_id']]; ?></div>
                                  <?php } ?>
                                </div>
                                <div class="col-xs-12">
                                  <span class="example_note">
                                    <ul>
                                       <li><?php echo $entry_withdraw_data_text; ?></li>
                                       <li><?php echo $entry_status_approve; ?></li>
                                       <li><?php echo $entry_description_text; ?></li>
                                       <li><?php echo $entry_date_text; ?></li>
                                    </ul>
                                    <pre class="example"><?php echo $entry_withdraw_setting_msg; ?></pre>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                   </fieldset>
                  </div>
                  <?php $active_is = ($active_is + 1); ?>
                  <?php } ?>
                </div>
              </div>
              <div class="tab-pane" id="tab-option">
                <fieldset class="well well-sm">
                  <legend class="mb0"><?php echo $entry_add_money_text; ?></legend>
                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label class="col-xs-12 control-label" for="input-min_add"><?php echo $entry_min_add; ?></label>
                        <div class="col-xs-12">
                          <input type="number" name="e_wallet_min_add" value="<?php echo $e_wallet_min_add; ?>" placeholder="<?php echo $entry_min_add; ?>" id="input-min_add" class="form-control" />
                          <?php if (isset($error_min_add)) { ?>
                          <div class="text-danger"><?php echo $error_min_add; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label class="col-xs-12 control-label" for="input-max_add"><?php echo $entry_max_add; ?></label>
                        <div class="col-xs-12">
                          <input type="number" name="e_wallet_max_add" value="<?php echo $e_wallet_max_add; ?>" placeholder="<?php echo $entry_max_add; ?>" id="input-max_add" class="form-control" />
                          <?php if (isset($error_max_add)) { ?>
                          <div class="text-danger"><?php echo $error_max_add; ?></div>
                          <?php } ?>
                        </div> 
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="well well-sm">
                  <legend class="mb0"><?php echo $entry_send_money_text; ?></legend>
                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label class="col-xs-12 control-label" for="input-min_send"><?php echo $entry_min_send; ?></label>
                        <div class="col-xs-12">
                          <input type="number" name="e_wallet_min_send" value="<?php echo $e_wallet_min_send; ?>" placeholder="<?php echo $entry_min_send; ?>" id="input-min_send" class="form-control" />
                          <?php if (isset($error_min_send)) { ?>
                          <div class="text-danger"><?php echo $error_min_send; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label class="col-xs-12 control-label" for="input-max_send"><?php echo $entry_max_send; ?></label>
                        <div class="col-xs-12">
                          <input type="number" name="e_wallet_max_send" value="<?php echo $e_wallet_max_send; ?>" placeholder="<?php echo $entry_max_send; ?>" id="input-max_send" class="form-control" />
                          <?php if (isset($error_max_send)) { ?>
                          <div class="text-danger"><?php echo $error_max_send; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="well well-sm">
                  <legend class="mb0"><?php echo $entry_withdraw_text; ?></legend>
                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label class="col-xs-12 control-label" for="input-min_send"><?php echo $entry_min_withdraw; ?></label>
                        <div class="col-xs-12">
                          <input type="number" name="e_wallet_min_withdraw" value="<?php echo $e_wallet_min_withdraw; ?>" placeholder="<?php echo $entry_min_withdraw; ?>" id="input-min_send" class="form-control" />
                          <?php if (isset($error_min_withdraw)) { ?>
                          <div class="text-danger"><?php echo $error_min_withdraw; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label class="col-xs-12 control-label" for="input-max_send"><?php echo $entry_max_withdraw; ?></label>
                        <div class="col-xs-12">
                          <input type="number" name="e_wallet_max_withdraw" value="<?php echo $e_wallet_max_withdraw; ?>" placeholder="<?php echo $entry_max_withdraw; ?>" id="input-max_send" class="form-control" />
                          <?php if (isset($error_max_withdraw)) { ?>
                          <div class="text-danger"><?php echo $error_max_withdraw; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <div class="row">
                  <div class="col-xs-12 col-sm-4">
                    <div class="row">
                      <label class="col-xs-12 control-label" for="input-complete_status"><?php echo $entry_complete_status; ?></label>
                      <?php if (isset($error_complete_status)) { ?>
                      <div class="text-danger"><?php echo $error_complete_status; ?></div>
                      <?php } ?>
                      <div class="col-xs-12">
                        <div class="well well-sm" style="min-height: 150px; overflow: auto;columns: 50px 3;">
                          <?php if (isset($order_statuses)) { ?>
                          <?php foreach($order_statuses as $_key => $order_statuse) { ?> 
                          <div class="">
                            <label>
                              <?php if ((is_array($e_wallet_complete_status) && in_array($order_statuse['order_status_id'], $e_wallet_complete_status))) { ?>
                            
                              <input type="checkbox" name="e_wallet_complete_status[]" value="<?php echo $order_statuse['order_status_id']; ?>" checked="checked" />&nbsp;
                              <?php echo $order_statuse['name']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="e_wallet_complete_status[]'}}" value="<?php echo $order_statuse['order_status_id']; ?>" />&nbsp;
                              <?php echo $order_statuse['name']; ?>
                              <?php } ?>
                              
                            </label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <div class="row">
                      <label class="col-xs-12 control-label" for="input-processing_status"><?php echo $entry_processing_status; ?></label>
                      <?php if (isset($error_processing_status)) { ?>
                      <div class="text-danger"><?php echo $error_processing_status; ?></div>
                      <?php } ?>
                      <div class="col-xs-12">
                        <div class="well well-sm" style="min-height: 150px; overflow: auto;columns: 50px 3;">
                          <?php if (isset($order_statuses)) { ?>
                          <?php foreach($order_statuses as $_key => $order_statuse) { ?> 
                          <div class="">
                            <label>
                              <?php if ((is_array($e_wallet_processing_status) && in_array($order_statuse['order_status_id'], $e_wallet_processing_status))) { ?>
                              <input type="checkbox" name="e_wallet_processing_status[]" value="<?php echo $order_statuse['order_status_id']; ?>" checked="checked" />&nbsp;
                              <?php echo $order_statuse['name']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="e_wallet_processing_status[]" value="<?php echo $order_statuse['order_status_id']; ?>" />&nbsp;
                              <?php echo $order_statuse['name']; ?>
                              <?php } ?>
                            </label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <div class="row">
                      <label class="col-xs-12 control-label" for="input-refund_order_id"><?php echo $entry_refund_order_id; ?></label>
                      <?php if (isset($error_refund_order_id)) { ?>
                      <div class="text-danger"><?php echo $error_refund_order_id; ?></div>
                      <?php } ?>
                      <div class="col-xs-12">
                        <div class="well well-sm" style="min-height: 150px; overflow: auto;columns: 50px 3;">
                          <?php if (isset($order_statuses)) { ?>
                          <?php foreach($order_statuses as $_key => $order_statuse) { ?> 
                          <div class="">
                            <label>
                              <?php if ((is_array($e_wallet_refund_order_id) && in_array($order_statuse['order_status_id'], $e_wallet_refund_order_id))) { ?>
                              <input type="checkbox" name="e_wallet_refund_order_id[]" value="<?php echo $order_statuse['order_status_id']; ?>" checked="checked" />&nbsp;
                              <?php echo $order_statuse['name']; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="e_wallet_refund_order_id[]" value="<?php echo $order_statuse['order_status_id']; ?>" />&nbsp;
                              <?php echo $order_statuse['name']; ?>
                              <?php } ?>
                            </label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <div class="row">
                      <label class="col-xs-12 control-label" for="input-payments"><?php echo $entry_payments; ?></label>
                      <div class="col-xs-12">
                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                           <?php if($payments){
                                foreach ($payments as $code => $payment) {
                                if($payment != 'e_wallet_payment') { ?>
                          <div class="checkbox">
                            <label>
                              <?php if ((is_array($e_wallet_payments) && in_array($code, $e_wallet_payments))) { ?>
                           
                              <input type="checkbox" name="e_wallet_payments[]" value="<?php echo $code; ?>" checked="checked" />&nbsp;
                              <?php echo $payment; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="e_wallet_payments[]" value="<?php echo $code; ?>" />&nbsp;
                              <?php echo $payment; ?>
                              <?php } ?>
                            </label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="row">
                      <label class="col-xs-12 control-label" for="input-payments"><?php echo $entry_checkout_payments; ?></label>
                      <div class="col-xs-12">
                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                          <?php if($payments) { ?> 
                          <?php foreach($payments as $code => $payment) { ?> 
                          <?php if($payment != 'e_wallet_payment' ) { ?>
                          <div class="checkbox">
                            <label>
                              <?php if ((is_array($e_wallet_checkout_payments) && in_array($code, $e_wallet_checkout_payments))) { ?>
                              <input type="checkbox" name="e_wallet_checkout_payments[]" value="<?php echo $code; ?>" checked="checked" />&nbsp;
                              <?php echo $payment; ?>
                              <?php } else { ?>
                              <input type="checkbox" name="e_wallet_checkout_payments[]" value="<?php echo $code; ?>" />&nbsp;
                              <?php echo $payment; ?>
                              <?php } ?>
                            </label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab-image">
                <div class="row">
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-icon"><?php echo $entry_icon; ?></label>
                      <div class="col-sm-10">
                        <a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $icon_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                        <input type="hidden" name="e_wallet_icon" value="<?php echo $e_wallet_icon; ?>" id="input-icon" />
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                      <label class="col-xs-12 control-label" for="input-image"><?php echo $entry_image; ?></label>
                      <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                        <input type="hidden" name="e_wallet_image" value="<?php echo $e_wallet_image; ?>" id="input-image" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                <div class="tab-pane" id="tab-bank">
                  <div class="row">
                   <div class="pull-right">
                     <button type="button" id="show" data-toggle="tooltip" title="" class="btn btn-primary add-main-menu"><i class="fa fa-plus"></i></button>&nbsp;
                    </div>
                  </div>
                  <div class="tab-content">
                    <div class="row">
                      <fieldset class="showmenu">
                         <div class="table-responsive">
                            <table id="option-value" class="table table-striped table-bordered table-hover" style="margin-top:15px;">
                              <thead>
                                    <tr>
                                      <td class="text-center"><?php echo $entry_feild_name; ?></td>
                                      <td class="text-center"><?php echo $entry_feild_key; ?></td>
                                      <td class="text-center"><?php echo $entry_feild_type; ?></td>
                                      <td class="text-center"><?php echo $entry_sort_order; ?></td>
                                      <td class="text-center"><?php echo $entry_text_required; ?></td>
                                      <td></td>
                                    </tr>
                                  </thead>
                              <tbody>
                                <?php $option_value_row = 0; ?>
                                <?php foreach($e_wallet_feild_data as $index => $module_e_wallet_feild_data) { ?> 
                                <tr id="option-value-row<?php echo $option_value_row; ?>">
                                  <td class="text-center">
                                    <?php foreach($languages as $_key => $language) { ?> 
                                      <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                        <input type="text" name="e_wallet_feild_data[<?php echo $option_value_row; ?>][name][<?php echo $language['language_id']; ?>]" style="min-width:100px;" value="<?php echo (($module_e_wallet_feild_data['name'][$language['language_id']]) ? ($module_e_wallet_feild_data['name'][$language['language_id']]) : ('')); ?>" placeholder="<?php echo $entry_option_value; ?>" class="form-control" />
                                      </div>
                                      <?php } ?>
                                      </td>
                                      <td class="text-right"><input type="text" name="e_wallet_feild_data[<?php echo $option_value_row; ?>][key]" value="<?php echo $module_e_wallet_feild_data['key']; ?>" class="form-control" />
                                        <?php if(isset($error_feild_data[$index])) { ?>
                                         <div class="text-danger text-left"><?php echo $error_feild_data[$index]; ?></div>
                                        <?php } ?></td>
                                      <td class="text-right"><select name="e_wallet_feild_data[<?php echo $option_value_row; ?>][type]" class="form-control" />
                                         <?php if ($module_e_wallet_feild_data['type'] == 'text') { ?>
                                              <option value="text" selected="selected"><?php echo $text_small; ?></option>
                                         <?php } else { ?>
                                              <option value="text"><?php echo $text_small; ?></option>
                                         <?php } ?>
                                         <?php if ($module_e_wallet_feild_data['type'] == 'textarea') { ?>
                                              <option value="textarea" selected="selected"><?php echo $text_big; ?></option>
                                         <?php } else { ?>
                                              <option value="textarea"><?php echo $text_big; ?></option>
                                         <?php } ?>
                                        </select>
                                      </td>
                                      <td class="text-right"><input type="text" name="e_wallet_feild_data[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $module_e_wallet_feild_data['sort_order']; ?>" class="form-control" /></td>
                                      <td class="text-right">
                                        <?php if (isset($module_e_wallet_feild_data['status'])) { ?>
                                        <input type="checkbox" name="e_wallet_feild_data[<?php echo $option_value_row; ?>][status]" value="1" checked="checked"/>
                                        <?php } else { ?>
                                        <input type="checkbox" name="e_wallet_feild_data[<?php echo $option_value_row; ?>][status]" value="0"/>
                                        <?php } ?>
                                      </td>
                                      <td class="text-right"><button type="button" onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                    </tr>
                                   <?php $option_value_row = ($option_value_row + 1); ?>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>
                      </fieldset>
                    </div>
                  </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css">
    .m0{margin: 0;}
    .mb0{margin-bottom: 0;}
  </style>
  <script type="text/javascript">
    <?php foreach($languages as $_key => $language) { ?> 
      $('#input-half_payment_line<?php echo $language['language_id']; ?>').summernote({height: 170});
      $('#input-full_payment_line<?php echo $language['language_id']; ?>').summernote({height: 170});
    <?php } ?>
  var option_value_row = <?php echo $option_value_row; ?>;
    function addFeildValue(){
      console.log(option_value_row);
      html  = '<tr id="option-value-row' + option_value_row + '">';
      html += '  <td class="text-left">';
      <?php foreach($languages as $_key => $language) { ?> 
      html += '    <div class="input-group">';
      html += '      <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="e_wallet_feild_data[' + option_value_row + '][name][<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_feild_name; ?>" class="form-control" />';
      html += '    </div>';
      <?php } ?>
      html += '  </td>';
      html += '  <td class="text-right"><input type="text" name="e_wallet_feild_data[' + option_value_row + '][key]" placeholder="<?php echo $entry_feild_key; ?>" value=""class="form-control" /></td>';
      html += '  <td class="text-right"><select name="e_wallet_feild_data[' + option_value_row + '][type]" class="form-control">';
      html += '  <option value="text"><?php echo $text_small; ?></option>';
      html += '  <option value="textarea"><?php echo $text_big; ?></option>';
      html += '  </select></td>';
      html += '  <td class="text-right"><input type="text" name="e_wallet_feild_data[' + option_value_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
      html += '<td class="text-right"><input type="checkbox" name="e_wallet_feild_data[' + option_value_row + '][status]" class="status_check" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control"></td>';
      html += '  <td class="text-right"><button type="button" onclick="$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
      html += '</tr>';
      $('#option-value > tbody').append(html);
      option_value_row++;
    }
  </script>
  <script type="text/javascript">
    $('.add-main-menu').click(function(){
       addFeildValue();
      return false;
    });
  </script>
  <?php echo $footer; ?>