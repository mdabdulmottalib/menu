<?php

// Facebook API credentials
$fb_page_access_token = 'EAAD8QomIkDUBO6mFMDGvgUgErTpUodwSBZBTjbR52dFPLSCE7A7BnTn8pgAnlyNphrrKpJa6BRbIzYUGwoNV4Ofp4XtYlTNNEEteSSx3KxZAmLH6CtuFiKAqF7syqnt6NWBBTuIUnlQmvZBus2MNvMWfsvUKGS9n7m1tWrHpcAdAYZBHkDegkyX65HfELWN9Iw89M826jgZDZD';
$fb_page_id = '276031529726223';

// Instagram API credentials
$ig_user_id = '49533997525';
$ig_access_token = 'EAAD8QomIkDUBO6mFMDGvgUgErTpUodwSBZBTjbR52dFPLSCE7A7BnTn8pgAnlyNphrrKpJa6BRbIzYUGwoNV4Ofp4XtYlTNNEEteSSx3KxZAmLH6CtuFiKAqF7syqnt6NWBBTuIUnlQmvZBus2MNvMWfsvUKGS9n7m1tWrHpcAdAYZBHkDegkyX65HfELWN9Iw89M826jgZDZD';

// Content to be posted
$content = "Herkullinen lounasbuffet on katettu! Tervetuloa!
Lounasbuffetimme on täynnä herkullisia vaihtoehtoja - runsas salaattipöytä ja monipuolinen lounasbuffet, joka sisältää 0,33L virvoitusjuoman, jälkiruoan sekä kahvin/teen.
Lounas tarjoillaan arkisin klo 10.00 - 15.00.
Voit myös soittaa meille ja varata pöydän etukäteen.
Löydät meidät osoitteesta Talvikkitie 38, 01300 Vantaa.
Tervetuloa herkuttelemaan kanssamme!";

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $image = $_FILES['image'];
    $image_path = 'uploads/' . basename($image['name']);

    // Move uploaded image to a folder
    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        // Post to Facebook
        postToFacebook($fb_page_id, $content, $image_path, $fb_page_access_token);

        // Post to Instagram
        postToInstagram($ig_user_id, $content, $image_path, $ig_access_token);

        echo "Posted successfully to Facebook and Instagram!";
    } else {
        echo "Failed to upload image.";
    }
} else {
    echo "No image uploaded or an error occurred.";
}

function postToFacebook($page_id, $message, $image_path, $access_token) {
    $url = "https://graph.facebook.com/{$page_id}/photos";
    $data = [
        'message' => $message,
        'access_token' => $access_token,
        'source' => new CURLFile($image_path)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function postToInstagram($user_id, $caption, $image_path, $access_token) {
    // Upload the image to Instagram
    $url = "https://graph.facebook.com/v17.0/{$user_id}/media";
    $data = [
        'image_url' => 'https://menu.pizzaexpress.fi/' . $image_path, // URL of the uploaded image
        'caption' => $caption,
        'access_token' => $access_token
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $response_data = json_decode($response, true);

    // If the image upload is successful, publish it
    if (isset($response_data['id'])) {
        $media_id = $response_data['id'];
        $publish_url = "https://graph.facebook.com/v17.0/{$user_id}/media_publish";
        $publish_data = [
            'creation_id' => $media_id,
            'access_token' => $access_token
        ];

        curl_setopt($ch, CURLOPT_URL, $publish_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($publish_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $publish_response = curl_exec($ch);
        curl_close($ch);

        return $publish_response;
    } else {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post to Social Media</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Post to Facebook & Instagram</button>
    </form>
</body>
</html>
