<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> Cancel</a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid"> <?php if ($error_warning) { ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
                    <fieldset>
                        <legend><?php echo $text_account; ?></legend>

                        {# <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" /> #}
                        <input type="hidden" name="customer_to_reseller_id" value="<?php echo $customer_to_reseller_id; ?>" />


                        <div class="form-group required">
                            <label class="col-sm-3" for="input-firstname"><?php echo $entry_customer_id; ?></label>
                            <div class="col-sm-9">
                               
                               <select <?php if ($customer_id) { ?> disabled <?php } ?> type="text" name="customer_id" placeholder="<?php echo $entry_customer; ?>" class="form-control" data-live-search="true">
                                            <?php foreach ($customers as $customer) { ?>
                                                <option <?php if ($customer_id == customer['customer_id']) { ?> selected <?php } ?> value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name']; ?> | <?php echo $customer['email']; ?></option>
                                            <?php } ?>
                                </select>
                                <?php if ($error_customer_id) { ?>
                                            <div class="text-danger"><?php echo $error_customer_id; ?></div>
                                 <?php } ?>
                                </div>
                        </div>
                        
                        <?php if ($customer_id) { ?>
                               <input type="hidden" name="customer_id"  value="<?php echo $customer_id; ?>" class="form-control" />
                        <?php } ?>

                        <?php if ($id_card_entry == 1 or  id_card_entry == 2) { ?>
                        <div class="form-group required">
                            <label class="col-sm-3" for="input-ktp"><?php echo $entry_ktp; ?></label>
                            <div class="col-sm-9">
                                <input type="text" name="ktp" value="<?php echo $ktp; ?>" placeholder="<?php echo $entry_ktp; ?>" id="input-ktp" class="form-control" />

                                <?php if ($error_ktp) { ?>
                                    <div class="text-danger"><?php echo $error_ktp; ?></div>
                                <?php } ?></div>
                        </div>
                        <?php } else { ?>
                            <input type="hidden" name="no_nik" value=""/>
                        <?php } ?>

                        {# <div class="form-group required">
                            <label class="col-sm-3" for="input-firstname"><?php echo $entry_firstname; ?></label>
                            <div class="col-sm-9">
                                <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                                <?php if ($error_firstname) { ?>
                                    <div class="text-danger"><?php echo $error_firstname; ?></div>
                                <?php } ?></div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-3" for="input-lastname"><?php echo $entry_lastname; ?></label>
                            <div class="col-sm-9">
                                <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                                <?php if ($error_lastname) { ?>
                                    <div class="text-danger"><?php echo $error_lastname; ?></div>
                                <?php } ?></div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-3" for="input-email"><?php echo $entry_email; ?></label>
                            <div class="col-sm-9">
                                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                <?php if ($error_email) { ?>
                                    <div class="text-danger"><?php echo $error_email; ?></div>
                                <?php } ?></div>
                        </div> #}
                         <div class="form-group required">
                            <label class="col-sm-3" for="input-telephone">
                                <h4><?php echo $entry_telephone; ?></h4>
                            </label>
                            <div class="col-sm-9">
                                <div class="col-sm-1" style="padding: 0;">
                                    <span class="marker-down"></span>
                                    <select data-live-search="true" data-selected="<?php echo $default_country; ?>" name="default_country" class="country_code">
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <input name="telephone" placeholder="<?php echo $entry_telephone; ?>"
                                           type="number" <?php echo $logged ? 'disabled' : ''; ?>
                                           name="telephone" value="<?php echo $telephone; ?>" id="input-telephone"
                                           class="form-control">
                                </div>
                                   <?php if ($error_telephone) { ?>
                                        <div class="text-danger"><?php echo $error_telephone; ?></div>
                                    <?php } ?>
                            </div>

                        </div>
                        <?php if ($social_media_profile) { ?>
                            <div class="form-group required">
                                <label class="col-sm-3" for="input-telephone"><?php echo $entry_facebook; ?></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span style="width:170px" class="input-group-addon">https://facebook.com/</span>
                                        <input type="text" name="facebook" value="<?php echo $facebook; ?>" placeholder="<?php echo $entry_facebook; ?>" id="input-telephone" class="form-control" />
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-3" for="input-telephone"><?php echo $entry_twitter; ?></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span style="width:170px" class="input-group-addon">https://twitter.com/</span>
                                        <input type="text" name="twitter" value="<?php echo $twitter; ?>" placeholder="<?php echo $entry_twitter; ?>" id="input-telephone" class="form-control" />
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-3" for="input-telephone"><?php echo $entry_instagram; ?></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span style="width:170px" class="input-group-addon">https://instagram.com/</span>
                                        <input type="text" name="instagram" value="<?php echo $instagram; ?>" placeholder="<?php echo $entry_instagram; ?>" id="input-telephone" class="form-control" />
                                    </div>
                                   
                                </div>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="instagram" value="" />
                            <input type="hidden" name="facebook" value="" />
                            <input type="hidden" name="twitter" value="" />
                        <?php } ?>

                        <div class="form-group">
                            <label class="col-sm-3" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                            <div class="col-sm-9">
                                <select name="customer_group_id" id="input-customer-group" class="form-control">
                                    <?php foreach ($customer_groups as $customer_group) { ?>
                                        <?php if ($customer_group['customer_group_id'] == customer_group_id) { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label class="col-sm-3" for="input-country-id">
                                <?php echo $entry_country; ?>
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
                                <?php if ($error_country_id) { ?>
                                            <div class="text-danger"><?php echo $error_country_id; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                         <div class="form-group required">
                            <label class="col-sm-3" for="input-zone-id">
                                <?php echo $entry_zone; ?>
                            </label>
                            <div class="col-sm-9">
                                <select name="zone_id" id="input-zone-id" class="form-control">
                                    <option value="0"><?php echo $text_select; ?></option>
                                </select>

                                 <?php if ($error_zone_id) { ?>
                                            <div class="text-danger"><?php echo $error_zone_id; ?></div>
                                 <?php } ?>
                            </div>
                        </div>


                        <div class="form-group required">
                            <label class="col-sm-3" for="input-address"><?php echo $entry_address; ?></label>
                            <div class="col-sm-9">
                                <textarea name="address" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control" rows="5" cols="10"><?php echo $address; ?></textarea>
                                <?php if ($error_address) { ?>
                                    <div class="text-danger"><?php echo $error_address; ?></div>
                                <?php } ?></div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend><?php echo $text_other; ?></legend>
                        <div class="form-group required">
                            <label class="col-sm-3">
                                <?php echo $entry_lampiran; ?>
                                <h6><?php echo $help_lampiran; ?></h6>
                            </label>
                            <div class="col-sm-3">
                                <button type="button" id="button-upload" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                            </div>
                            <input type="hidden" name="lampiran" value="<?php echo $lampiran; ?>" id="upload_lampiran" />
                            <div class="col-sm-3 <?php echo $upload_link ? 'alert alert-success' : ''; ?>" id="filename">
                                <?php if ($upload_link) { ?>
                                    <a href="<?php echo $upload_link['href']; ?>"><?php echo $upload_link['value']; ?></a>
                                <?php } ?>
                            </div>
                            <?php if ($error_lampiran) { ?>
                                <div class="text-danger"><?php echo $error_lampiran; ?></div>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3" for="input-status"><?php echo $entry_status; ?></label>
                            <div class="col-sm-9">
                                <select name="status" id="input-status" class="form-control">
                                    <?php if ($status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save Reseller</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        <!--
       $("select[name='customer_id']").selectpicker();

        const availabel_countries = <?php echo $availabel_countries; ?>;

        $('select[name=\'country_id\']').on('change', function() {

            $('select[name=\'zone_id\']').val(0)

            $.ajax({
                url: 'index.php?route=localisation/country/country&token=<?php echo $token; ?>&country_id=' + this.value,
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
                    html = '<option value=""><?php echo $text_select; ?></option>';

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

        $(document).ready(function () {
            fetch("view/javascript/selectpicker/js/countryPhone.json").then(res => res.json()).then(json => {
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
                })
            });
        });

        $('select[name=\'user_group_id\']').on('change', function() {
            $.ajax({
                url: 'index.php?route=customer/customer/customfield&token=<?php echo $token; ?>&user_group_id=' + this.value,
                dataType: 'json',
                success: function(json) {
                    $('.custom-field').hide();
                    $('.custom-field').removeClass('required');

                    for (i = 0; i < json.length; i++) {
                        custom_field = json[i];

                        $('.custom-field' + custom_field['custom_field_id']).show();

                        if (custom_field['required']) {
                            $('.custom-field' + custom_field['custom_field_id']).addClass('required');
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });

        $('select[name=\'user_group_id\']').trigger('change');
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('#history').delegate('.pagination a', 'click', function(e) {
            e.preventDefault();

            $('#history').load(this.href);
        });

        $('#history').load('index.php?route=customer/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

        $('#button-history').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'index.php?route=customer/customer/addhistory&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
                type: 'post',
                dataType: 'json',
                data: 'comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
                beforeSend: function() {
                    $('#button-history').button('loading');
                },
                complete: function() {
                    $('#button-history').button('reset');
                },
                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['error']) {
                        $('#tab-history').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                    if (json['success']) {
                        $('#tab-history').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        $('#history').load('index.php?route=customer/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

                        $('#tab-history textarea[name=\'comment\']').val('');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('#transaction').delegate('.pagination a', 'click', function(e) {
            e.preventDefault();

            $('#transaction').load(this.href);
        });

        $('#transaction').load('index.php?route=customer/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

        $('#button-transaction').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'index.php?route=customer/customer/addtransaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
                type: 'post',
                dataType: 'json',
                data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
                beforeSend: function() {
                    $('#button-transaction').button('loading');
                },
                complete: function() {
                    $('#button-transaction').button('reset');
                },
                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['error']) {
                        $('#tab-transaction').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                    if (json['success']) {
                        $('#tab-transaction').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        $('#transaction').load('index.php?route=customer/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

                        $('#tab-transaction input[name=\'amount\']').val('');
                        $('#tab-transaction input[name=\'description\']').val('');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('#reward').delegate('.pagination a', 'click', function(e) {
            e.preventDefault();

            $('#reward').load(this.href);
        });

        $('#reward').load('index.php?route=customer/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

        $('#button-reward').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'index.php?route=customer/customer/addreward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
                type: 'post',
                dataType: 'json',
                data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
                beforeSend: function() {
                    $('#button-reward').button('loading');
                },
                complete: function() {
                    $('#button-reward').button('reset');
                },
                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['error']) {
                        $('#tab-reward').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                    if (json['success']) {
                        $('#tab-reward').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        $('#reward').load('index.php?route=customer/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

                        $('#tab-reward input[name=\'points\']').val('');
                        $('#tab-reward input[name=\'description\']').val('');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });

        $('#ip').delegate('.pagination a', 'click', function(e) {
            e.preventDefault();

            $('#ip').load(this.href);
        });

        $('#ip').load('index.php?route=customer/customer/ip&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

        $('#content').delegate('button[id^=\'button-custom-field\'], button[id^=\'button-address\']', 'click', function() {
            var node = this;

            $('#form-upload').remove();

            $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

            $('#form-upload input[name=\'file\']').trigger('click');

            if (typeof timer != 'undefined') {
                clearInterval(timer);
            }

            timer = setInterval(function() {
                if ($('#form-upload input[name=\'file\']').val() != '') {
                    clearInterval(timer);

                    $.ajax({
                        url: 'index.php?route=tool/upload/upload&token=<?php echo $token; ?>',
                        type: 'post',
                        dataType: 'json',
                        data: new FormData($('#form-upload')[0]),
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $(node).button('loading');
                        },
                        complete: function() {
                            $(node).button('reset');
                        },
                        success: function(json) {
                            $(node).parent().find('.text-danger').remove();

                            if (json['error']) {
                                $(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
                            }

                            if (json['success']) {
                                alert(json['success']);
                            }

                            if (json['code']) {
                                $(node).parent().find('input[type=\'hidden\']').val(json['code']);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            }, 500);
        });

        $('.date').datetimepicker({
            language: '<?php echo $datepicker; ?>',
            pickTime: false
        });

        $('.datetime').datetimepicker({
            language: '<?php echo $datepicker; ?>',
            pickDate: true,
            pickTime: true
        });

        $('.time').datetimepicker({
            language: '<?php echo $datepicker; ?>',
            pickDate: false
        });

        // Sort the custom fields
        <?php $address_row = 1; ?> <?php foreach ($addresses as $address) { ?>
        $('#tab-address<?php echo $address_row; ?> .form-group[data-sort]').detach().each(function() {
            if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-address<?php echo $address_row; ?> .form-group').length) {
                $('#tab-address<?php echo $address_row; ?> .form-group').eq($(this).attr('data-sort')).before(this);
            }

            if ($(this).attr('data-sort') > $('#tab-address<?php echo $address_row; ?> .form-group').length) {
                $('#tab-address<?php echo $address_row; ?> .form-group:last').after(this);
            }

            if ($(this).attr('data-sort') < -$('#tab-address<?php echo $address_row; ?> .form-group').length) {
                $('#tab-address<?php echo $address_row; ?> .form-group:first').before(this);
            }
        }); <?php $address_row = address_row + 1; ?> <?php } ?>

        $('#tab-customer .form-group[data-sort]').detach().each(function() {
            if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-customer .form-group').length) {
                $('#tab-customer .form-group').eq($(this).attr('data-sort')).before(this);
            }

            if ($(this).attr('data-sort') > $('#tab-customer .form-group').length) {
                $('#tab-customer .form-group:last').after(this);
            }

            if ($(this).attr('data-sort') < -$('#tab-customer .form-group').length) {
                $('#tab-customer .form-group:first').before(this);
            }
        });

        $('#tab-affiliate .form-group[data-sort]').detach().each(function() {
            if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-affiliate .form-group').length) {
                $('#tab-affiliate .form-group').eq($(this).attr('data-sort')).before(this);
            }

            if ($(this).attr('data-sort') > $('#tab-affiliate .form-group').length) {
                $('#tab-affiliate .form-group:last').after(this);
            }

            if ($(this).attr('data-sort') < -$('#tab-affiliate .form-group').length) {
                $('#tab-affiliate .form-group:first').before(this);
            }
        });
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('input[name=\'payment\']').on('change', function() {
            $('.payment').hide();

            $('#payment-' + this.value).show();
        });

        $('input[name=\'payment\']:checked').trigger('change');


        $('button[id^=\'button-upload\']').on('click', function() {
            var node = this;

            $('#form-upload').remove();

            $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

            $('#form-upload input[name=\'file\']').trigger('click');

            timer = setInterval(function() {
                if ($('#form-upload input[name=\'file\']').val() != '') {
                    clearInterval(timer);

                    $.ajax({
                        url: 'index.php?route=reseller/reseller/upload&token=<?php echo $token; ?>',
                        type: 'post',
                        dataType: 'json',
                        data: new FormData($('#form-upload')[0]),
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $(node).button('loading');
                        },
                        complete: function() {
                            $(node).button('reset');
                        },
                        success: function(json) {
                            $('.text-danger').remove();

                            if (json['error']) {
                                $('input[name=\'code\']').after('<div class="text-danger">' + json['error'] + '</div>');
                            }

                            if (json['success']) {
                                $('#filename').html(json['success']);
                                $('#filename').addClass("alert alert-danger");

                                $('input[name=\'lampiran\']').attr('value', json['code']);

                                $('#form-upload').remove();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            }, 100);
        });
        //-->
    </script>
</div>
<style type="text/css">
    h6 {
        line-height: 1.3em;
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
<?php echo $footer; ?>
