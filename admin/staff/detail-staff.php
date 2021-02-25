<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");

$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["user_login_id"]) && isset($_GET["skill_id"])){
    $user_login_id = $Encryption->decode($_GET["user_login_id"]);
    $skill_id = $Encryption->decode($_GET["skill_id"]);
    $result = send_rest(array(
        "function" => "Admin/GetCustomerDetails",
		"customer_id" => $user_login_id
    ));
    if(empty($result['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row = $result['data'];
    }

    $result1 = send_rest(array(
        "function" => "Admin/GetSkillDetails",
		"skill_id" => $skill_id
    ));
    if(empty($result1['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row1 = $result1['data'];
    }
}
if($error==0){
?>

<script>
	$(document).ready(function(){
		$("#SaveStaffForm").validationEngine({
			promptPosition: "topRight:-90"
		});
	});
</script>

<div class="container-fluid">
    <div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">
			    <div class="row">
			        <div class="col-4">
			            <div><?php if(!empty($d_row)){ echo 'Edit ';}else{ echo "Add ";}?> Staff</div>
			        </div>
				</div>
            </div>
        </div> 
    </div>
    <div class="table-div mt-1">
        <div class="row">
            <div class="col-lg-12">
                <?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
                <form id="SaveStaffForm" data-new-key="staff_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/staff/staff-ajax.php";?>" data-add-url="<?php echo ADMIN_URL."/staff/detail-staff.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
                    <div class="row ">
                        <div class="form-group col-lg-4 col-md-6">
                            <input type="hidden" name="action" value="SaveStaff">
                            <input type="hidden" value="<?php if(!empty($_GET["skill_id"])){echo $_GET["skill_id"];} ?>" name="skill_id">
                            <input type="hidden" value="<?php if(!empty($_GET["user_login_id"])){echo $_GET["user_login_id"];} ?>" name="user_login_id">
                            <label for="first_name">First Name<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required]" id="first_name" name="first_name" value='<?php if(!empty($d_row)){ echo $d_row['first_name']; }?>' placeholder="Enter First Name">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="last_name">Last Name<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required]" id="last_name" name="last_name" value='<?php if(!empty($d_row)){ echo $d_row['last_name']; }?>' placeholder="Enter Last Name">
                        </div>
                        <div class="form-group col-lg-4 col-md-6 ">
                            <label for="email">Email<span class="required_star">*</span></label>
                            <div class="email-star">
                            <input type="text" class="form-control validate[required] custom[email]" id="email" name="email" value='<?php if(!empty($d_row)){ echo $d_row['email']; }?>'placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="form-group col-lg-4 col-md-6 ">
                            <label for="password">Password<span class="required_star">*</span></label>
                            <div class="password-star">
                            <input type="text" class="form-control validate[required] custom[email]" id="email" name="email" value='<?php if(!empty($d_row)){ echo $d_row['email']; }?>'placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="skill">Skilled In</label>
                            <input type="text" class="form-control custom[number] min[0]" id="skill" name="skill" value='<?php if(!empty($d_row1)){ echo $d_row1['skill_name']; }?>' placeholder="Enter Skill">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="created_on">Created On</label>
                            <input type="text" class="form-control" id="created_on" name="created_on" value="<?php if(!empty($d_row)){ echo $d_row['created_on']; }?>" disabled>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="modified_on">Modified On</label>
                            <input type="text" class="form-control" id="modified_on" name="modified_on" value="<?php if(!empty($d_row)){ echo $d_row['modified_on']; }?>" disabled>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                             <div class="toggleWrapper">
                                <input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle" id="is_enabled"  value="1" <?php if(!empty($d_row) && $d_row['is_enabled']==0){ } else echo"checked";?>>
                                <label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
                            </div>
                        </div>
                    </div>        
                    <button type="submit" class="btn theme-btn float-md-right save_btn_action" data-form-id="SaveStaffForm">Save Changes</button>
                    <button type="button" class="cancel_btn_action cancel-btn btn btn-default">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <div class="copyright-text mt-4">Copyrights 2020, Dowcon</div>
</div>
<?php
}
include_once(ADMIN_DIR . "/footer.php");
?>