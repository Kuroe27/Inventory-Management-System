<?php
// Set the upload folder path
$target_dir = "../images/";


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the file was uploaded without errors
  if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
    // Set the file name and path
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;

    // Check if the file already exists
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
    } else {
      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file " . $file_name . " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>

<!-- HTML form to upload the image -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
  <input type="file" name="image" id="image">
  <input type="submit" value="Upload Image" name="submit">
</form>
