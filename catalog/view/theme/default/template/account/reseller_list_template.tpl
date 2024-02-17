 <div id="list-reseller">
      <?php foreach ($resellers as $reseller) { ?>
        <div class="list-reseller col-sm-6 col-md-4 col-lg-4 col-xl-4 col-12">
            <div class="reseller-info">
            <div class="seller-name"><?php echo $reseller['name']; ?></div>
            <div class="seller-info"><?php echo $reseller['type']; ?></div>   
            <div class="seller-location"><?php echo $reseller['zone']; ?><?php if ($reseller['country']) { ?>, <?php echo $reseller['country']; ?><?php } ?></div>

            <div id="btn-reseller">
               <?php if ($reseller['whatsapp']) { ?>
                    <a href="<?php echo $reseller['whatsapp']; ?> " type="button" class="btn"><span><i class="fa fa-whatsapp" aria-hidden="true"></i> <?php echo $text_social_whatsapp; ?></span></a>
               <?php } ?>
               
               <?php if ($reseller['instagram']) { ?>
                    <a href="<?php echo $reseller['instagram']; ?>" type="button" class="btn"><span><i class="fa fa-instagram" aria-hidden="true"></i> <?php echo $reseller['instagram_name']; ?></span></a>
               <?php } ?>
            </div>
            </div>
        </div>
       <?php } ?>
</div>

 <div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

<script>
    $('.pagination li a').each(function(i,v){

         let url = $(v).attr('href');

         $(v).attr('data-url', url);

         $(v).removeAttr('href');
    });

  
</script>