<?php echo $header; ?>
    <?php echo $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                 <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
            </div>
        </div>

        <div class="container-fluid">

            <?php if (!$curl_status) { ?>
            <fieldset>
                <legend> <?php echo $text_curl; ?> </legend>
                <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $text_disabled_curl; ?></div>
                </fieldset>
           <?php } ?>


         <fieldset>
                <legend> Store Validation </legend>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_validate_store; ?></div>
            <p>How to get license key:</p>
            <ol>
                <li>Login to hpwebdesign.id / hpwebdesign.io</li>
                <li>Go to Downloads page. Navigate to Downloads Profile.</li>
                <li>Add your domain.</li>
                <li>Copy your license file into the following form</li>
                <li>Click on Validate</li>
            </ol>
            <legend>License Validation</legend>

<form id="license-form">
<div class="form-group">
        <label for="input-status" class="col-sm-2 control-label">License Key
        </label>
        <div class="col-sm-4">
         <input name="license_key" type="text" value="" class="form-control">
          <input name="extension_code" type="hidden" value="<?php echo $extension_code; ?>" class="form-control">
        </div>
        <div class="col-sm-4">
        <button type="button" onclick="saveLicense();" id="button-license" data-loading-text="Loading" class="btn btn-primary">Validate</button>
        </div>
       </div>
</form>


<div style="clear: both;width: 100%;height: 30px;"></div>
<div class="alert alert-info"><i class="fa fa-info-circle"></i> Having issue on validation?</div>
<a href="mailto:support@hpwebdesign.id?subject=<?php echo $extension_code; ?> f|| $demo06.hpwebdesign.id" class="btn btn-info"><i class="fa fa-phone"></i> Contact Us</a>
         </fieldset>
        </div>
    </div>
<script>
function saveLicense() {
$('.alert').remove();

let domain = 'epinsgamestore.com';
let extension_type = '<?php echo $extension_type; ?>';
let license_key = $('input[name="license_key"]').val();
let extension_code = $('input[name="extension_code"]').val();
var license = license_key.split('-');

var the_data = {'extension_type' : extension_type, 'license_key' : license_key, 'extension_code': extension_code,'domain' : domain };

 $.ajax({
        url: 'index.php?route=common/hp_validate/licensewalker&token=<?php echo $token; ?>',
        type: 'post',
        data: the_data,
        dataType: 'json',
        beforeSend: function() {
            $('#button-license').button('loading');
        },
         complete: function () {
         $('#button-license').button('reset');
                },
        success: function(json) {

            console.log(json);

            if (json['success']) {

                    $('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
            }

            if (json['error']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-times-circle"></i> ' + json['message'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });


}
</script>
<?php echo $footer; ?>
