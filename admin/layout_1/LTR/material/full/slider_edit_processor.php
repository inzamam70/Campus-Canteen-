<?php include_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php') ?>
<?php


$src = null;
$old_picture = null;
$new_picture = null;
$old_picture = $_post['old_picture'];

if (array_key_exists('picture', $_FILES) && !empty($_FILES['picture']['name'])) {
    $filename = $_FILES['picture']['name'];
    $filename = uniqid() . "_" . $_FILES['picture']['name'];
    $target = $_FILES['picture']['tmp_name'];
    $destination = $uploads . $filename;

    if (upload($target, $destination)) {
        $new_picture = $filename;
    }

    if (file_exists($uploads . $old_picture)) {
        unlink($uploads . $old_picture);
    }
}

$id = $_POST['id'];
$uuid = $_POST['uuid'];

$src = $new_picture ?? $old_picture;


$alt =  $_POST['alt'];
$title = $_POST['title'];
$caption = $_POST['caption'];


$slide = [
    'id' => $id,
    'uuid' => $uuid,
    'src' => $src,
    'alt' => $alt,
    'tittle' => $title,
    'caption' => $caption
];



$sliderjason = file_get_contents($frontenddatasource . "slider.json");
$slideritems = json_decode($sliderjason);

foreach ($slideritems as $key => $aslide) {
    if ($aslide->id == $id)
        break;
}

$slideritems[$key] = (object) $slide;
$data_slides = json_encode($slideritems);

// dd($data_slides);


if (file_exists($frontenddatasource . "slider.json")) {
    $result = file_put_contents($frontenddatasource . "slider.json", $data_slides);
} else {
    echo "file not found";
}
if ($result) {
    $message = "Data is updated";
    set_session('message', $message);
    // redirect("slider_index.php?message =".$message);
    redirect("slider_index.php");
}
