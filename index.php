<?php
require_once('simplesaml.inc');

/*# SAML Data #*/
$user_sso = $_simplesamlphp_auth_attributes['UID'][0];
$user_email = $_simplesamlphp_auth_attributes['Email'][0];
$user_accountstatus = $_simplesamlphp_auth_attributes['AccountStatus'][0];
$user_firstname = $_simplesamlphp_auth_attributes['FirstName'][0];
$user_lastname = $_simplesamlphp_auth_attributes['LastName'][0];
$user_fullname = $user_firstname.' '.$user_lastname;
/*# SAML Data #*/

//define('IMAGE_SIZE', 1024);
define('IMAGE_SIZE', 1024);
define('IMAGE_HEIGHT', 154);

$error = 0;
$success = 0;
$image_exists = 0;
$disabled = "disabled";
$extensions = array('jpg', 'JPG', 'jpeg', 'JPEG');
$profilepic_filepath = "photo/";

/*# DEFAULT Profile Pic - START #*/
if (!empty($user_sso)) {
	$disabled = "";
	if (file_exists($profilepic_filepath.$user_sso.".jpg")) {
		$user_image = $profilepic_filepath.$user_sso.".jpg";
		$image_exists = 1;
  } elseif (file_exists($profilepic_filepath.$user_sso.".jpeg")) {
		$user_image = $profilepic_filepath.$user_sso.".jpeg";
		$image_exists = 1;
	} else {
		$user_image = "images/default_user_160x120.jpg";
		$image_exists = 0;
	}
} else {
	$user_image = "images/default_user_160x120.jpg";
  $image_exists = 0;
}
/*# DEFAULT Profile Pic - END #*/

/*# UPLOAD Profile Pic - START #*/
if (isset($_POST['upload-submit'])) {
	$image_exists = $_POST['image-exists'];
	$user_sso = $_POST['user-sso'];

	if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
    $size = $_FILES['image']['size']/1024;
    if ($size < IMAGE_SIZE) {
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      if (in_array($ext, $extensions)) {
        $tmp = $_FILES['image']['tmp_name'];

        $image_name = $user_sso.'.'.strtolower($ext);

        $profile_pic = $profilepic_filepath.strtolower($image_name);

        del_profilepic($profilepic_filepath.$user_sso, $extensions);
        
        if (move_uploaded_file($tmp, $profile_pic)) {
          /* $size = getimagesize($profile_pic);
          $width = round(IMAGE_HEIGHT*$size[0]/$size[1]);
          switch ($ext) {
            case 'jpg':
                $image_original = ImageCreateFromJPEG($profile_pic);
                break;
            case 'png':
                $image_original = ImageCreateFromPNG($profile_pic);
                break;
          }
          $photoX = ImagesX($image_original);
          $photoY = ImagesY($image_original);
          $image_resized = ImageCreateTrueColor($width, IMAGE_HEIGHT);
          ImageCopyResampled($image_resized, $image_original, 0, 0, 0, 0, $width+1, IMAGE_HEIGHT+1, $photoX, $photoY);
          ImageJPEG($image_resized, $profile_pic);
          ImageDestroy($image_original);
          ImageDestroy($image_resized); */
          
          chmod($profile_pic, 0775);
      
          $success = 1;
          $success_message = 'Image uploaded successfully.';
          header("Refresh:0");
        }
      } else {
        $error = 1;
        //$error_message = 'Image file type must be .jpg or .png!';
        $error_message = 'Image file type must be jpg/JPG or jpeg/JPEG!!';
      }
    } else {
      $error = 1;
      $error_message = 'Image file size must be 1MB or less!!';
    }		
	} else {
    $error = 1;
    $error_message = 'Please select an image for uploading!!';
  }
}
/*# UPLOAD Profile Pic - START #*/

/*# DELETE Profile Pic - START #*/

function del_profilepic($profilepic_filepath, $extensions) {
  foreach($extensions as $k=>$v) {
    if( file_exists($profilepic_filepath.'.'.$v) ) {
      unlink($profilepic_filepath.'.'.$v);
    }
  }
}

if(isset($_POST['delete-submit'])) {
	$image_exists = $_POST['image-exists'];
	$user_sso = $_POST['user-sso'];
	
	// Check whether user has already uploaded an image
  if ($image_exists) {
    $file_name = $_POST['file_name'];
    //$file_name = "photo/".$user_sso.".jpg";
    if (file_exists($file_name)) {
      if (unlink($file_name)) {
        $success = 1;
        $success_message = 'Image deleted successfully.';
        header("Refresh:0");
      }
    }
  } else {
    $error = 1;
    $error_message = 'Image doesn\'t exist!';
  }
}
/*# DELETE Profile Pic - END #*/
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">		
		<title>Profile Image Upload</title>
		<link rel="Stylesheet" type="text/css" href="css/upload.css" />
	</head>
	<body>
		<div id="upload-form-wrapper">
			<div id="upload-form-header">
				<div class="secondary-menu show-for-large">
          <div class="row">
            <div class="clearfix">
              <div class="float-right">
                <ul>
                  <li><span>Welcome <strong><?php echo $user_fullname; ?></strong></span></li>
                  <!--<li><span><a class="new-item" href="logout.php" title="Logout">LOGOUT</a></span></li>-->
                </ul>
              </div>
            </div>
          </div>
        </div>
				<div id="nbcunow-logo-wrapper">
					<div class="nbcunow-logo"></div>
				</div>
				<div id="header-label-wrapper">
					<span class="header-label"><strong>Submit your photo</strong></span>
				</div>
			</div>
			<div id="upload-form-content">
				<form id="upload" method="post" enctype="multipart/form-data" action="/">			
					<div class="header-form-label">
						<b>Please select an image of yourself to upload.</b><br><font class="header-form-content">Best size 154px by 154px.<br/>Picture file type must be .jpg/.JPG or .jpeg/.JPEG. File size must be 1MB or less.</font>
					</div>
					<div class="header-form-desc">
						<div class="header-photo-upload-desc">
							Loading your photo into this site is strictly voluntary. If you choose to load a photograph/image into this site, please use an image that is appropriate for a business directory (e.g., a head and shoulders image of you dressed in business or business casual attire). By loading your photograph/image into this site, you are authorizing its use throughout SupportCentral, including the HR Organization Directory, and internal HR and other internal business applications, e.g., WebEx, organization charts in internal business presentations. Your image will not be used or sold for any external or marketing purposes without your express prior consent.
						</div>
						</br>
			<?php if($error){ ?>
              <div class="error"><?php print $error_message; ?></div>
            <?php } ?>
            <?php if($success) { ?>
              <div class="success"><?php print $success_message; ?></div>
            <?php } ?>
            <input id="upload-image" type="file" name="image">
						<input type="hidden" name="user-sso" id="user-sso" value="<?php print $user_sso; ?>">
						<input type="hidden" name="image-exists" id="image-exists" value="<?php print $image_exists; ?>">
						<input type="hidden" name="file_name" id="user-image" value="<?php print $user_image; ?>">
						<div id="image-container">
							<img id="user-image" src="<?php print $user_image; ?>" width="154" height="154" alt="Image preview...">
						</div>
						<?php if(!empty($image_exists)) { ?>
							<p class="header-form-label"><strong>Profile Photo URL:</strong> <a target="_blank" class="new-item" href="<?php print $user_image; ?>"><?php print 'http://'.$_SERVER['SERVER_NAME'].'/'.$user_image; ?></a></p>
						<?php } ?>					
						<!--(Note: The image will be resized to 3:4 ratio -- width=150px height=200px for HR applications, width=60px height=80px for SupportCentral)<BR>-->						
					</div>
					<div id="upload-form-submit">
						<input type="submit" name="upload-submit" id="upload-submit" value="Submit Photo" <?php print $disabled; ?>>
						<input type="submit" name="delete-submit" id="delete-submit" value="Delete Photo" <?php print $disabled; ?>>
						<span id="loading" style="display:none;"><img src="images/loading.gif"></span>
					</div>
					<div class="nbcunow-footer-copyright">
						<span>&copy; <?php print date('Y'); ?> NBCUniversal, Inc. All Rights Reserved</span>
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/upload.js"></script>
	</body>
</html>