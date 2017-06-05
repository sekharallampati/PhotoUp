jQuery( document ).ready(function(e) {

	jQuery( "#upload-image" ).change(function (e) {

		var preview = document.querySelector('img');
		var imageFile    = this.files[0];
    
    if(this.value.length == 0) {
      preview.src = 'images/default_user_160x120.jpg';
    }
		
		//var approvedFileTypes = ['jpg', 'png'];
		var approvedFileTypes = ['jpg', 'JPG', 'jpeg', 'JPEG'];
		var fileType = imageFile.name.split('.').pop().toLowerCase();
		var isSuccess = approvedFileTypes.indexOf(fileType) > -1;
		
		if(isSuccess){
			var fileSize = parseInt(imageFile.size/1024);
			if(fileSize > 1024){
				alert("Image file size must be 1MB or less!");
        jQuery( "#upload-image" ).val('');
        preview.src = jQuery("#image-container #user-image").attr("src");
        //preview.src = 'images/default_user_160x120.jpg';
				return false;
			}else{
				var reader  = new FileReader();

				reader.addEventListener("load", function () {
					preview.src = reader.result;
				}, false);
				
				if (imageFile) {
					reader.readAsDataURL(imageFile);
				}
	
				return true;
			}
		}else{
			alert("Image file type must be .jpg/JPG or .jpeg/JPEG!!");
      jQuery( "#upload-image" ).val('');
      preview.src = jQuery("#image-container #user-image").attr("src");
      //preview.src = 'images/default_user_160x120.jpg';
			return false;
		}
	});
	
	jQuery( "#upload-submit" ).click(function() {
		if(jQuery( "#upload-image" ).val() == '') {
			alert("Please select an image for uploading!!");
			return false;
		}
	});
		
	jQuery( "#delete-submit" ).click(function() {
		var image_exists = jQuery( "#image-exists" ).val();
		if(image_exists == 1){
			if(confirm("Are you sure you want to delete this image?")){
				return true;
			}else{
				return false;
			}
		}else{
			alert("Image doesn't exist!");
			return false;
		}
	});
});