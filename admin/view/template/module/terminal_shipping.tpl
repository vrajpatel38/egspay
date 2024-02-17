<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
          <div class="pull-right">
            <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
          <h1><?php echo $heading_title; ?></h1>
          <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body"> 
        <div id="form-speedup" class="form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-key" data-toggle="tab">key</a></li>
                <li><a href="#tab-terminal-africa" data-toggle="tab">Terminal Africa</a></li>
                <li><a href="#tab-wallet" data-toggle="tab">Wallet</a></li>
                <li><a href="#tab-address" data-toggle="tab">Address</a></li>
                <li><a href="#tab-carriers" data-toggle="tab">Carriers</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-key">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_public_key; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo isset($pk) ? $pk : ''; ?>" name="public_key" required id="public_key" placeholder="Enter your public key">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_secret_key; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo isset($sk) ? $sk : ''; ?>" name="secret_key" required id="secret_key" placeholder="Enter your secret key">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <p>
                        You can get your keys from your <br><a href="https://app.terminal.africa/settings/api-keys-webhooks" target="_blank">Terminal Africa Dashboard</a>
                        </p>
                        <button class="btn btn-primary" id="t_form">Connect to Terminal Africa</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-terminal-africa">
                    <h1>terminal africa</h1>
                </div>
                <div class="tab-pane" id="tab-wallet">
                    <div class="terminal-standard-wrapper">
                        <div class="terminal-standard-block">
                            <div class="t-wallet-background t-wallet-home">
                                <div class="t-balance-container">
                                    <?php
                                    foreach ($wallet_data as $balance) :
                                    ?>
                                        <div class="terminal-balance-block t-<?php echo $balance['currency']; ?>-balance" data-balance="<?php echo $balance['balance']; ?>">
                                            <h1 class="t-wallet-balance-title"><?php echo $balance['currency']; ?> Balance</h1>
                                            <div class="t-balance-figure"><?php echo $balance['currency'] == "NGN" ? '₦' : '$'; ?><?php echo $balance['balance']; ?></div>
                                            <div class="t-balance-footer-text">Total available including pending transactions</div>
                                        </div>
                                    <?php
                                    endforeach;
                                    ?>
                                </div>
                                <div class="t-balance-container">
                                    <div class="t-landing-action-link-button">
                                        <a class="t-wallet-link-wrapper t-top-up-landing" onclick="gotoTerminalPage(this, 't-wallet-topup')"><img src="<?php echo $walleticon; ?>" loading="lazy" height="30" width="30" alt="" class="t-top-up-image-green-landscape">
                                            <div class="t-quick-link-text">Top Up</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="t-wallet-background t-wallet-topup" style="display:none ;">
                                <div class="t-terminal-dashboard-back-link-block"><a class="t-terminal-dashboard-back-link" onclick="gotoTerminalPage(this, 't-wallet-home')">&#8592; Wallet</a></div>
                                <div class="t-top-up-wallet-wrapper t-amount-input">
                                    <h4 class="t-wallet-heading-text">Enter topup amount</h4>
                                    <div class="t-topup-amount-block"><input placeholder="₦0.00" class="t-top-up-amount-input" step=".01" data-max="0" min="0" type="number" value=""></div>
                                    <div>
                                        <div class="t-balance-sub-text">Balance after topup - ₦0.00</div>
                                        <select class="t-switch-wallet w-select">
                                            <option value="NGN">Nigerian Naira (₦)</option>
                                        </select>
                                    </div>
                                    <div class="t-wallet-label">SELECT A PAYMENT METHOD</div>
                                    <ul role="list" class="t-topup-wallet-list-wrapper w-list-unstyled">
                                        <li data-method="bank-transfer" class="t-list-item-section active bottom"><img data-method="bank-transfer" src="<?php echo $bank_icon_orange ?>" loading="lazy" height="50" width="60" alt="" class="t-topup-icon">
                                            <div data-method="bank-transfer" class="t-topup-method-block">
                                                <h3 data-method="bank-transfer" class="t-topup-method-heading">Bank Transfer</h3>
                                                <div data-method="bank-transfer" class="t-topup-method-text">Top up by sending money to a Nigerian bank account</div>
                                            </div>
                                            <div class="t-wallet-option-check-icon">
                                                <img src="<?php echo $bank_check ?>" height="30" width="30" alt="check">
                                            </div>
                                        </li>
                                    </ul>
                                    <div>
                                        <a class="t-topup-cta-link w-inline-block" onclick="gotoTerminalPage(this, 't-confirm-bank')">
                                            Next
                                        </a>
                                    </div>
                                </div>
                                <!-- T-Bank Account -->
                                <div class="t-confirm-top-up-wallet-block t-confirm-bank" style="display:none ;">
                                    <h4 class="t-wallet-heading-text">Make a Transfer of</h4>
                                    <div class="t-top-up-amount">₦1,000.00</div>
                                    <div class="t-balance-sub-text">to this account</div>
                                    <ul role="list" class="t-bank-details-list w-list-unstyled">
                                        <li class="t-bank-details-list-item">
                                            <img src="<?php echo $bank_icon_orange; ?>" loading="lazy" width="60" alt="" class="t-bank-account-icon">
                                            <div class="t-bank-info-text bank-details"><?php echo $other_data ? $other_data->bank_name : 'NULL' ?></div>
                                            <h3 class="t-ban-account-number bank-details"><?php echo $other_data ? $other_data->account_number : 'NULL' ?></h3>
                                            <div class="t-bank-info-text bank-details"><?php echo $other_data ? $other_data->account_name : 'NULL' ?></div>
                                        </li>
                                    </ul>
                                    <div><a class="t-topup-cta-link orange w-inline-block" onclick="confirmTerminalTransfer(this, event)">
                                            <div>Confirm</div>
                                        </a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-address">
                    <div class="t-container">
                        <div class="t-body">
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <form method="post" id="t-form-submit" data-type="merchant">
                                    <fieldset>
                                        <legend>Pickup Address</legend>
                                            <div class="t-address-info">
                                                <!-- instructions -->
                                                <h3 class="t-title">
                                                    <strong>Instructions:</strong>
                                                </h3>
                                                <p class="t-text">
                                                    Please fill in your address details below. This address will be used to pick up your items from your location.
                                                </p>
                                            </div>
                                           
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="first_name">First Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="first_name" required id="first_name" class="form-control" placeholder="First Name" value="<?php echo isset($merchant_address['first_name']) ? $merchant_address['first_name'] : ''; ?>" onkeyup="updateData(this,event, 't_first_name')">
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="last_name">Last Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="last_name" required id="last_name" class="form-control" placeholder="Last Name" value="<?php echo isset($merchant_address['last_name']) ? $merchant_address['last_name'] : ''; ?>" onkeyup="updateData(this,event, 't_last_name')">
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="email">Email</label>
                                                <div class="col-sm-10">
                                                    
                                                    <input type="email" name="email" required id="email" class="form-control" placeholder="Email" value="<?php echo isset($merchant_address['email']) ? $merchant_address['email'] : ''; ?>" onkeyup="updateData(this,event, 't_email')">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="phone">Phone</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="phone" required id="phone" class="form-control" placeholder="Phone" value="<?php echo isset($merchant_address['phone']) ? $merchant_address['phone'] : ''; ?>" onkeyup="updateData(this,event, 't_phone')">
                                                </div>
                                            </div>
                                               
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="street_address">Street Address</label>
                                                <div class="col-sm-10">
                                                    <input type="text" required name="line_1" id="street_address" class="form-control" placeholder="Street Address" value="<?php echo isset($merchant_address['line1']) ? $merchant_address['line1'] : ''; ?>" onkeyup="updateData(this,event, 't_address')">
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="line_2">Line 2</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="line_2" id="line_2" class="form-control" placeholder="Line 2" value="<?php echo isset($merchant_address['line2']) ? $merchant_address['line2'] : ''; ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="country">Country</label>
                                                <div class="col-sm-10">    
                                                    <select class="form-control t-terminal-country" required name="country" id="">
                                                        <option value="">Country</option>
                                                        <?php foreach ($countries as $key => $value) : ?>
                                                            <option value="<?php echo $value->isoCode; ?>" data-flag="<?php echo $value->flag; ?>" <?php echo $merchant_address && $merchant_address['country'] == $value->isoCode ? 'selected' : ''; ?>>
                                                                <?php echo $value->name; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="state">State</label>
                                                <div class="col-sm-10">    
                                                    <select class="form-control t-terminal-state" required name="state" id="">
                                                        <option value="">State</option>
                                                        <?php foreach ($states['data'] as $key => $value) :
                                                            //get $saved_address_state
                                                            // if ($saved_address && $saved_address->state == $value->name) {
                                                            //     $saved_address_state = $value->isoCode;
                                                            // }
                                                        ?>
                                                            <option value="<?php echo $value->name; ?>" data-statecode="">
                                                                <?php echo $value->name; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="lga">LGA</label>
                                                <div class="col-sm-10">    
                                                    <select class="form-control t-terminal-city" required name="lga" id="">
                                                        <?php
                                                        if ($saved_address) :
                                                            $lga = get_terminal_cities($saved_address->country, $saved_address_state);
                                                            //check if cities is empty
                                                            if (!empty($lga['data'])) :
                                                                foreach ($lga['data'] as $key => $value) : ?>
                                                                    <option value="<?php echo esc_html($value->name); ?>" <?php echo $saved_address && $saved_address->city == $value->name ? 'selected' : ''; ?>>
                                                                        <?php echo esc_html($value->name); ?>
                                                                    </option>
                                                            <?php endforeach;
                                                            endif;
                                                        else :  ?>
                                                            <option value="">LGA</option>
                                                        <?php
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="zip_code">Zip Code</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="Zip Code" value="<?php echo isset($merchant_address['zip']) ? $merchant_address['zip'] : ''; ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-2"></div>
                                                <div class="t-col-10">
                                                    <button type="button" id="t_form_submit_address" class="t-address-save btn btn-primary">Save Changes</button>
                                                </div>
                                            </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 d-xs-none">
                                <div class="t-display-information t-data-profile">
                                    <h3 class="t-title">Personal Information</h3>
                                    <p id="t_first_name">
                                        <?php echo isset($merchant_address['last_name']) ? $merchant_address['first_name'] : ''; ?>
                                    </p>
                                    <p id="t_last_name">
                                        <?php echo isset($merchant_address['last_name']) ? $merchant_address['last_name'] : ''; ?>
                                    </p>
                                    <p id="t_email">
                                        <?php echo isset($merchant_address['email']) ? $merchant_address['email'] : ''; ?>
                                    </p>
                                    <p id="t_phone">
                                        <?php echo isset($merchant_address['phone']) ? $merchant_address['phone'] : ''; ?>
                                    </p>
                                    <h3 class="t-title" style="padding-top:6px;">Address</h3>
                                    <p id="t_address">
                                        <?php echo isset($merchant_address['line1']) ? $merchant_address['line1'] : ''; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-carriers">
                    <!-- <h1> Carriers</h1> -->
                    <div class="col-12">
                        <div class="t-address-card-carriers t-settings-section">
                            <div class="t-carrier-card-header">
                                <h4 class="t-address-card-header-text t-pl-0">Terminal Settings</h4>
                            </div>
                            <div class="t-carrier-card-body">
                                <div class="row t-justify-content-between">
                                    <div class="col-2 col-md-4 col-md-12">
                                        <div class="t-carriers-custom-markup">
                                            <!-- custom price mark up -->
                                            <h3 class="t-title t-mb-1">
                                                Custom price mark up:
                                            </h3>
                                            <p class="t-mb-2">
                                                Enter a custom price mark up for all your shipments in percentage (%)
                                            </p>
                                            <input type="number" class="form-control" name="terminal_custom_price_mark_up" placeholder="e.g 10 for 10%" id="terminal_custom_price_mark_up" value="<?php echo $terminal_percentage_custom_price_mark_up; ?>">
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-6 col-md-12">
                                        <div class="t-carriers-custom-markup">
                                            <!-- custom price mark up -->
                                            <h3 class="t-title t-mb-1">
                                                Default Currency Code:
                                            </h3>
                                            <p class="t-mb-2">
                                                Set the default currency code for the checkout page.
                                            </p>
                                            <select class="form-control t-terminal-country-default-settings" name="terminal_default_currency_code" id="">
                                                <option value="">Country</option>
                                                <?php foreach ($countries as $key => $country) : ?>
                                                    <option value="<?php echo $country->currency; ?>" data-isocode="<?php echo $country->isoCode; ?>" data-flag="<?php echo $country->flag; ?>" <?php echo $saved_currency && $saved_currency['isoCode'] == $country->isoCode ? 'selected' : ''; ?>>
                                                        <?php echo $country->name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    foreach ($carriersData as $type => $carrierData) :
                        $title = $carrierData['title'];
                        $carriers = $carrierData['carriers'];
                        $userCarriers = $carrierData['userCarriers'];
                        $ajaxuserCarriers = json_encode($userCarriers);
                    ?>
                        <div class="col-12">
                            <div class="t-address">
                                <div class="t-address-card-carriers">
                                    <div class="t-carrier-card-header">
                                        <h4 class="t-address-card-header-text"><?php echo $title; ?></h4>
                                    </div>
                                    <div class="t-carrier-card-body">
                                        <?php
                                        foreach ($carriers as $carrier) :
                                            $carrier_name = $carrier->name;
                                            $carrier_logo = $carrier->logo;
                                            $carrier_id = $carrier->carrier_id;
                                            $carrier_active = $carrier->active;
                                            $domestic = $carrier->domestic ? 'true' : 'false';
                                            $international = $carrier->international ? 'true' : 'false';
                                            $regional = $carrier->regional ? 'true' : 'false';
                                            $slug = $carrier->slug;

                                        ?>
                                            <div class="t-carrier-region-listing-block" data-domestic="<?php echo $domestic; ?>" data-international="<?php echo $international; ?>" data-regional="<?php echo $regional; ?>">
                                                <div class="t-carrier-name-wrapper">
                                                    <div class="t-carrier-logo-wrapper">
                                                        <div class="t-carrier-logo-block dhl" style="background-image: url('<?php echo $carrier_logo; ?>');"></div>
                                                    </div>
                                                    <div class="t-carrier-name-block">
                                                        <div class="t-carrier-name"><?php echo $carrier_name; ?></div>
                                                    </div>
                                                    <?php
                                                    if (!$carrier_active) :
                                                    ?>
                                                        <div class="t-carrier-coming-soon">Coming Soon</div>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </div>
                                                <?php
                                                if ($carrier_active) :
                                                ?>
                                                    <div class="t-carrier-embed w-embed">
                                                        <label class="t-switch t-carrier-switch">
                                                            <input type="checkbox" data-type="<?php echo $type; ?>" data-carrier-id="<?php echo $carrier_id; ?>" data-usercarriers='<?php echo $ajaxuserCarriers; ?>' class="t-carrier-checkbox">
                                                            <span class="t-slider round"></span>
                                                        </label>
                                                    </div>
                                                <?php
                                                endif;
                                                ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        //check if its last item
                        if ($carrierData !== end($carriersData)) :
                        ?>
                            <div class="t-col-12">
                                <div class="t-space-no-border"></div>
                            </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
           </div>
        </div>
      </div>
    </div>
    <div id="image-crush-results"></div>
</div>
<?php 
// if ($packaging_id == "no") {
//   loadTerminalPackaging();
// }
?>
<script type="text/javascript">

    let updateData = (elem, e, element_id) => {
      //prevent default
      e.preventDefault();
      jQuery(document).ready(function ($) {
        //get the value
        let value = $(elem).val();
        //get the element
        let element = $(`#${element_id}`);
        //update the value
        element.text(value);
      });
    };

    let gotoTerminalPage = (elem, page) => {
      jQuery(document).ready(function ($) {
        let page1 = ["t-wallet-home", "t-wallet-topup"];
        let page2 = ["t-amount-input", "t-confirm-bank"];
        //if page is in page1
        if (page1.includes(page)) {
          //remove active class from all page1
          $.each(page1, function (i, v) {
            $(`.${v}`).hide();
          });
          //check if session storage is set
          if (sessionStorage.getItem("bank") === "true") {
            //remove active class from all page2
            $.each(page2, function (i, v) {
              $(`.${v}`).hide();
            });
            //add active class to page
            $(`.t-wallet-topup, .t-amount-input`).show();
            //clear session storage
            sessionStorage.clear();
            // console.log("session storage cleared");
            return;
          }
          //add active class to page
          $(`.${page}`).show();
        }
        //if page is in page2
        if (page2.includes(page)) {
          //check if session storage is
          if (sessionStorage.getItem("amount") !== "true") {
            //Swal
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Please enter amount first!",
              confirmButtonColor: "rgb(246 146 32)",
              cancelButtonColor: "rgb(0 0 0)",  
            });
            return;
          }
          //remove active class from all page2
          $.each(page2, function (i, v) {
            $(`.${v}`).hide();
          });
          //add active class to page
          $(`.${page}`).show();
          //check if page is t-confirm-bank then session storage
          if (page === "t-confirm-bank") {
            sessionStorage.setItem("bank", "true");
            // console.log("session storage set");
          } else {
            //remove session storage
            sessionStorage.removeItem("bank");
            // console.log("session storage removed");
          }
        }
      });
    };

    //load terminal_packaging

    let loadTerminalPackaging = () => {
      jQuery(document).ready(function ($) {
        $.ajax({
              type: "GET",
              url: 'index.php?route=module/terminal_shipping/get_terminal_packaging'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
              data: {
                action: "get_terminal_packaging"
              },
              dataType: "json",
              success: function (response) {
                console.log(response);
              }
        });
      });
    };

    //.t-top-up-amount-input keyup and change
    jQuery(document).ready(function ($) {
      $(document).on("keyup change", ".t-top-up-amount-input", function () {
        let amount = $(this).val();
        //check if amount is empty
        if (amount === "") {
          //disable button
          $(".t-top-up-amount-btn").attr("disabled", true);
          //remove session storage
          sessionStorage.removeItem("amount");
          return;
        }
        //enable button
        $(".t-top-up-amount-btn").attr("disabled", false);
        //session storage
        sessionStorage.setItem("amount", "true");
        //get old balance
        let oldBalance = $(".t-NGN-balance");
        let amount2 = amount;
        //check if element exist
        if (oldBalance.length) {
          //get data balance
          let dataBalance = oldBalance.data("balance");
          //convert to number
          let balance = Number(dataBalance);
          //convert amount to number
          amount = Number(amount);
          //add amount to balance
          amount = balance + amount;
        }
        //format to price format
        let formattedAmount = new Intl.NumberFormat("en-NG", {
          style: "currency",
          currency: "NGN"
        }).format(amount);
        //format amount2
        let formattedAmount2 = new Intl.NumberFormat("en-NG", {
          style: "currency",
          currency: "NGN"
        }).format(amount2);
        //set amount to display
        $(".t-balance-sub-text:first").html(
          `Balance after topup - ${formattedAmount}`
        );
        $(".t-top-up-amount").html(formattedAmount2);
      });
    });

    let confirmTerminalTransfer = (elem, e) => {
      //prevent default
      e.preventDefault();
      jQuery(document).ready(function ($) {
        //Swal success
        Swal.fire({
          icon: "success",
          title: "Top Up Completed!",
          confirmButtonColor: "rgb(246 146 32)",
          //confirm button text
          confirmButtonText: "Continue",
          text: "You should receive confirmation once your transfer is confirmed."
        }).then(() => {
          //reload page
          location.reload();
        });
      });
    };

    $(document).ready(function() {
        $('.t-carrier-checkbox').each(function() {
            var checkbox = $(this);
            var carrierId = checkbox.data('carrier-id');
            var usercarriers = checkbox.data('usercarriers');
            var type =  checkbox.data('type');
            $.ajax({
                url: 'index.php?route=module/terminal_shipping/getActiveCarrier'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
                method: 'POST',
                data: {
                    carrier_id: carrierId,
                    usercarriers: usercarriers,
                    type: type
                },
                dataType: 'json',
                success: function(response) {
                    if (response.code == 200 && response.data === true) {
                        checkbox.prop('checked', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });

    $("#terminal_custom_price_mark_up").on("focusout", function (e) {
    //get value
    let value = $(this).val();
    //ajax
    $.ajax({
      type: "POST",
      url: 'index.php?route=module/terminal_shipping/save_terminal_custom_price_mark_up'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
      data: {
        action: "save_terminal_custom_price_mark_up",
        percentage: value
      },
      dataType: "json",
      beforeSend: () => {
        //izitoast
        iziToast.show({
          theme: "dark",
          title: "Saving custom price mark up",
          position: "topRight",
          progressBarColor: "rgb(246 146 32)",
          transitionIn: "fadeInDown",
          timeout: false
        });
      },
      success: function (response) {
        //close izitoast
        iziToast.destroy();
        if (response.code == 200) {
          //izitoast
          iziToast.success({
            title: "Success",
            message: response.message,
            position: "topRight",
            progressBarColor: "rgb(246 146 32)",
            transitionIn: "fadeInDown"
          });
        } else {
          //izitoast
          iziToast.error({
            theme: "dark",
            title: "Error",
            message: response.message,
            position: "topCenter",
            progressBarColor: "rgb(246 146 32)",
            transitionIn: "fadeInDown"
          });
        }
      },
      error: function (error) {
        console.log(error);
      }
    });
  });

    jQuery(document).ready(function ($) {
        //auth
        $("#t_form").click(function (e) {
            e.preventDefault();
            // var form = $(this);
            var public_key = $("#public_key").val();
            var secret_key = $("#secret_key").val();
            // console.log(public_key)
            $.ajax({
                type: "POST",
                url: 'index.php?route=module/terminal_shipping/terminal_africa_auth'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
                data: {
                    action: "terminal_africa_auth",
                    public_key: public_key,
                    secret_key: secret_key
                },
                dataType: "json",
              
                success: function (response) {
                    if (response.code == 200) {
                        Swal.fire({
                        icon: "success",
                        type: "success",
                        title: "Success!",
                        text: "Terminal Africa has been successfully authenticated",
                        confirmButtonColor: "rgb(246 146 32)",
                        cancelButtonColor: "rgb(0 0 0)",
                        iconColor: "rgb(246 146 32)",
                      }).then((result) => {
                        if (result.value) {                          
                            //show loading
                            Swal.fire({
                              title: "Page reloading...",
                              text: "Please wait...",
                              // imageUrl: terminal_africa.plugin_url + "/img/loader.gif",
                              allowOutsideClick: false,
                              allowEscapeKey: false,
                              allowEnterKey: false,
                              showConfirmButton: false,
                            });
                            location.reload(true);
                        }
                      });
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                            confirmButtonColor: "rgb(246 146 32)",
                            cancelButtonColor: "rgb(0 0 0)",
                        });
                    }
                },
            });
        });
    });

    //each element
    $(".t-carrier-switch").each(function (i, v) {
    //find input checkbox and change event
    $(v)
      .find("input")
      .on("change", function (e) {
        //get parent
        let parent = $(this).parents(".t-carrier-region-listing-block");
        // console.log(parent)
        //carrier id
        let carrier_id = $(this).data("carrier-id");
        // console.log(carrier_id)
        //domestic
        let domestic = parent.data("domestic");
        //international
        let international = parent.data("international");
        //regional
        let regional = parent.data("regional");
        //create carrier object
        let carrierObj = {
          id: carrier_id,
          domestic,
          international,
          regional
        };
        //check if all are not empty
        if (
          carrier_id !== "" &&
          carrier_id !== undefined &&
          carrier_id !== null &&
          carrier_id !== "null" &&
          carrier_id !== "undefined" &&
          domestic !== "" &&
          domestic !== undefined &&
          domestic !== null &&
          domestic !== "null" &&
          domestic !== "undefined" &&
          international !== "" &&
          international !== undefined &&
          international !== null &&
          international !== "null" &&
          international !== "undefined" &&
          regional !== "" &&
          regional !== undefined &&
          regional !== null &&
          regional !== "null" &&
          regional !== "undefined"
        ) {
          //check if input is checked
          if ($(this).is(":checked")) {
            //update server
            $.ajax({
              type: "POST",
              url: 'index.php?route=module/terminal_shipping/update_user_carrier_terminal'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
              data: {
                action: "update_user_carrier_terminal",
                // nonce: terminal_africa.nonce,
                carrierObj,
                status: "enabled"
              },
              dataType: "json",
              beforeSend: () => {
                $('#image-crush-results').html('<img src="https://i.gifer.com/origin/34/34338d26023e5515f6cc8969aa027bca_w200.gif" width="100px" />');
              },
              success: function (response) {
                $('#image-crush-results').empty();
                //unblock element
                // $(parent).unblock();
                //izitoast success if response code is 200
                if (response.code == 200) {
                  iziToast.success({
                    title: "Success",
                    message: response.message,
                    position: "topRight",
                    timeout: 3000,
                    transitionIn: "flipInX",
                    transitionOut: "flipOutX"
                  });
                }
              }
            });
          } else {
            //update server
            $.ajax({    
              type: "POST",
              url: 'index.php?route=module/terminal_shipping/update_user_carrier_terminal'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
              data: {
                action: "update_user_carrier_terminal",
                // nonce: terminal_africa.nonce,
                carrierObj,
                status: "disabled"
              },
              dataType: "json",
              beforeSend: () => {
                $('#image-crush-results').html('<img src="https://i.gifer.com/origin/34/34338d26023e5515f6cc8969aa027bca_w200.gif" width="100px" />');
              },
              success: function (response) {
                $('#image-crush-results').empty();
                //unblock element
                // $(parent).unblock();
                //izitoast success if response code is 200
                if (response.code == 200) {
                  iziToast.info({
                    title: "Success",
                    message: response.message,
                    position: "topRight",
                    timeout: 3000,
                    transitionIn: "flipInX",
                    transitionOut: "flipOutX"
                  });
                }
              }
            });
          }
          return;
        }
        //log data are empty
        console.log("data are empty", carrierObj);
      });
  });

    //t-form-submit
    $("#t_form_submit_address").click(function (e) {
    //prevent default
    e.preventDefault();
    //form
    var form = $('#t-form-submit');
    // var formData = new FormData(form);
    //get type
    var type = form.data("type");
    var actionData = "terminal_merchant_save_address";
    //if type is customer
    if (type == "customer") {
      actionData = "terminal_customer_save_address";
    }
    //ajax
    $.ajax({
      type: "POST",
      url: 'index.php?route=module/terminal_shipping/terminal_merchant_save_address'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
      data:
        form.serialize() + "&action=" + actionData,
      // data:formData,
      dataType: "json",
      beforeSend: function () {
        // Swal loader
        Swal.fire({
          title: "Processing...",
          text: "Please wait...",
          // imageUrl: terminal_africa.plugin_url + "/img/loader.gif",
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          showConfirmButton: false,
          
        });
      },
      success: function (response) {
        //close loader
        Swal.close();
        //check response is 200
        if (response.code == 200) {
          //swal success
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: response.message,
            confirmButtonColor: "rgb(246 146 32)",
            cancelButtonColor: "rgb(0 0 0)",
            iconColor: "rgb(246 146 32)",
            
          }).then((result) => {
            if (result.value) {
              //if type is customer
              if (type == "customer") {
                //Swal alert 'Customer address changed, please recalculate shipping'
                Swal.fire({
                  icon: "info",
                  title: "Info",
                  text: "Customer address changed, please recalculate shipping fee",
                  confirmButtonColor: "rgb(246 146 32)",
                  cancelButtonColor: "rgb(0 0 0)",
                  iconColor: "rgb(246 146 32)",
                  
                }).then((result) => {
                  if (result.value) {
                    //trigger click #t-carrier-change-button
                    $("#t-carrier-change-button").trigger("click");
                  }
                });
              } else {
                //reload page
                location.reload();
              }
            }
          });
        } else {
          //swal error
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: response.message,
            confirmButtonColor: "rgb(246 146 32)",
            cancelButtonColor: "rgb(0 0 0)",
            
          });
        }
      },
      error: function (error) {
        //close loader
        Swal.close();
        //swal error
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Something went wrong!",
          confirmButtonColor: "rgb(246 146 32)",
          cancelButtonColor: "rgb(0 0 0)",
          
        });
      }
    });
  });

    //event onchange
    $(".t-terminal-country").change(function (e) {
    //prevent default
    e.preventDefault();
    //get value
    var country = $(this).val();
    //check country
    if (country) {
      //ajax
      $.ajax({
        type: "POST",
        url: 'index.php?route=module/terminal_shipping/get_states'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
        data: {
          action: "terminal_africa_get_states",
          countryCode: country
        },
        dataType: "json",
        beforeSend: function () {
          // Swal loader
          Swal.fire({
            title: "Processing...",
            text: "Please wait...",
            // imageUrl: terminal_africa.plugin_url + "/img/loader.gif",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            
          });
        },
        success: function (response) {
          //close loader
          Swal.close();
          //check response is 200
          if (response.code == 200) {
            //destroy select2
            // $(".t-terminal-state").select2("destroy");
            //remove all options
            // $(".t-terminal-state").find("option").remove();
            //append options
            // $(".t-terminal-state").append(
            //   "<option value=''>Select State</option>"
            // );
            //loop
            // $.each(response.states, function (key, value) {
            //   //append options
            //   $(".t-terminal-state").append("<option value='" + value.name + "' data-statecode='" + value.isoCode + "'>" + value.name + "</option>" );
            // });

            
            if(Object.entries(response.data).length){
                var State_html= "<option value=''>Select State</option>";
                $.each(response.data, function(key, value) {
                    State_html += `<option value='${value.name}' data-statecode='${value.isoCode}'>${value.name}</option>`;
                });
                $('[name="state"]').html(State_html);
            }
            
            //init select2 .t-terminal-state
            // $(".t-terminal-state").select2({
            //   placeholder: "Select state",
            //   allowClear: true,
            //   width: "100%",
            //   //select class
            //   dropdownCssClass: "t-form-control",
            //   //dropdown parent
            //   dropdownParent: $(".t-terminal-state").parent()
            // });
          } else {
            //destroy select2
            // $(".t-terminal-state").select2("destroy");
            //remove all options
            // $(".t-terminal-state").find("option").remove();
            //append options
            $(".t-terminal-state").append(
              "<option value=''>Select State</option>"
            );
            //init select2 .t-terminal-state
            // $(".t-terminal-state").select2({
            //   placeholder: "Select state",
            //   allowClear: true,
            //   width: "100%",
            //   //select class
            //   dropdownCssClass: "t-form-control",
            //   //dropdown parent
            //   dropdownParent: $(".t-terminal-state").parent()
            // });
            //swal error
            Swal.fire({
              icon: "error",

              title: "Oops...",
              text: response.message,
              confirmButtonColor: "rgb(246 146 32)",
              cancelButtonColor: "rgb(0 0 0)",
              //footer
              
            });
          }
        },
        error: function (error) {
          //close loader
          Swal.close();
          //swal error
          Swal.fire({
            icon: "error",

            title: "Oops...",
            text: "Something went wrong!",
            confirmButtonColor: "rgb(246 146 32)",
            cancelButtonColor: "rgb(0 0 0)",
            
          });
        }
      });
    }
  });

    //event onchange
    $(".t-terminal-state").change(function (e) {
    //prevent default
    e.preventDefault();
    //get value
    var state = $(this).find("option:selected").data("statecode");
    var country = $(".t-terminal-country").val();
    //check if country is selected
    if (!country) {
      //swal error
      Swal.fire({
        icon: "error",

        title: "Oops...",
        text: "Please select country first!",
        confirmButtonColor: "rgb(246 146 32)",
        cancelButtonColor: "rgb(0 0 0)",
        
      });
      //return
      return;
    }
    //check state
    if (state && country) {
      //ajax
      $.ajax({
        type: "POSt",
        url: 'index.php?route=module/terminal_shipping/get_terminal_cities'+'&<?php echo $token; ?>'+'&_='+(new Date()).getTime(),
        data: {
          action: "terminal_africa_get_cities",
          stateCode: state,
          countryCode: country
        },
        dataType: "json",
        beforeSend: function () {
          // Swal loader
          Swal.fire({
            title: "Processing...",
            text: "Please wait...",
            // imageUrl: terminal_africa.plugin_url + "/img/loader.gif",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            
          });
        },
        success: function (response) {
          //close loader
          Swal.close();
          //check response is 200
          if (response.code == 200) {
            //destroy select2
            // $(".t-terminal-city").select2("destroy");
            //remove all options
            // $(".t-terminal-city").find("option").remove();
            //append options
            // $(".t-terminal-city").append(
            //   "<option value=''>Select City</option>"
            // );
            //loop
            if(Object.entries(response.data).length){
                var city_html = "<option value=''>Select City</option>";
                $.each(response.data, function (key, value) {
                  //append options
                    city_html += `<option value='${value.name}'>${value.name}</option>`;
                });
                $('[name="lga"]').html(city_html);
                    // $(".t-terminal-city").append(
                    //     "<option value='" + value.name + "'>" + value.name + "</option>"
                    // );
            }
            //init select2 .t-terminal-city
            // $(".t-terminal-city").select2({
            //   placeholder: "Select city",
            //   allowClear: true,
            //   width: "100%",
            //   //select class
            //   dropdownCssClass: "t-form-control",
            //   //dropdown parent
            //   dropdownParent: $(".t-terminal-city").parent()
            // });
          } else {
            //destroy select2
            // $(".t-terminal-city").select2("destroy");
            //remove all options
            // $(".t-terminal-city").find("option").remove();
            //append options
            $(".t-terminal-city").append(
              "<option value=''>Select City</option>"
            );
            //init select2 .t-terminal-city
            // $(".t-terminal-city").select2({
            //   placeholder: "Select city",
            //   allowClear: true,
            //   width: "100%",
            //   //select class
            //   dropdownCssClass: "t-form-control",
            //   //dropdown parent
            //   dropdownParent: $(".t-terminal-city").parent()
            // });
            //swal error
            Swal.fire({
              icon: "error",

              title: "Oops...",
              text: response.message,
              confirmButtonColor: "rgb(246 146 32)",
              cancelButtonColor: "rgb(0 0 0)",
              
            });
          }
        },
        error: function (error) {
          //close loader
          Swal.close();
          //swal error
          Swal.fire({
            icon: "error",

            title: "Oops...",
            text: "Something went wrong!",
            confirmButtonColor: "rgb(246 146 32)",
            cancelButtonColor: "rgb(0 0 0)",
            
          });
        }
      });
    } else {
      //destroy select2
      
      //remove all options
      
      //append options
      $(".t-terminal-city").append("<option value=''>Select City</option>");
      //init select2 .t-terminal-city
      // $(".t-terminal-city").select2({
      //   placeholder: "Select city",
      //   allowClear: true,
      //   width: "100%",
      //   //select class
      //   dropdownCssClass: "t-form-control",
      //   //dropdown parent
      //   dropdownParent: $(".t-terminal-city").parent()
      // });
      //log
      console.log("Please select state first!", state, country);
    }
  });
</script>