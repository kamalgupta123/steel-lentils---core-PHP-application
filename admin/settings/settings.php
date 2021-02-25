<?php
include_once(__DIR__ .'/../header.php');
include_once(ADMIN_DIR .'/admin-functions.php');
    
$postcode_details = send_rest(array(
    "function" => "Admin/getPostCodes"
));
$postcode_details = $postcode_details['data'];

?>
<script>
$(document).ready(function(){
	$("#PostCodeSaveForm").validationEngine({
		promptPosition: "topRight:-90"
	});
});
</script>
<div class="container-fluid">
<div class="row head-row">
    <div class="col-lg-12">
        <div class="heading-div ">
        Accepted delivery Postcodes
        </div>
  
<div class="setting-div">
<form id="PostCodeSaveForm" class="submit_ajax" data-reload='true' data-action-url="<?php echo ADMIN_URL."/settings/settings-ajax.php"; ?>" data-back-url="">
    <input type='hidden' name='action' value='postcodes_save'>
    <div class="container" id='postcodes'>
        <div class='post_inputs'>
        <?php 
            if(!empty($postcode_details)){
                $i=1;
                foreach($postcode_details as $postcode){ 
                ?>
                <div class='row append_to'>
                    <div class='col-lg-4'>
                         <input type='text' class='form-control post_input validate[required,custom[postcode]] minSize[4]' name='postcodes[]' value='<?php echo $postcode['postcode'];?>'>
                    </div>
                    <?php if($i!=1){ ?>
                        <div class='col-lg-2'>
                            <button type='button' class='btn btn-danger remove_postcode'><i class='fa fa-minus'></i></button>
                        </div>
                    <?php } ?>
                </div>
                <?php $i++;  } }else{  ?>
                <div class="row append_to">
                    <div class="col-lg-4 col-9">
                        <input type='text' class='form-control validate[required,custom[postcode]] post_input minSize[4]' id="postcode_name"  name='postcodes[]' value='' placeholder='Enter Postcode'>      
                    </div>
                </div>
                </div>
        <?php } ?>
        </div>
        <div class='row col-12'>
            <button type='button' class='btn btn-success' id="add_postcode"><i class='fa fa-plus'></i></button>
        </div>
    </div>
  
    <div class="row mt-3">
        <div class="col-lg-12 text-left">
                <button type="submit" class="btn theme-btn btn-flat save_btn_action" data-form-id="PostCodeSaveForm">SAVE</button>
        </div>
    </div>
</form>
</div>
    </div>
</div>
</div>


<?php
include_once(__DIR__ .'/../footer.php');
?>
