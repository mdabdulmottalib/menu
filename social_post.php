<?php
function postToSocialMedia($imagePath, $caption) {
    $fbAccessToken = 'EAAD8QomIkDUBO08KKcJmBbG7U9ba9zkeZCGNciTE7h8sD28Wnfsc0bXZAFQSmVCSYt1NnfbcuU9BJAVNdU4n3pkC7xf3MltIhTdSg3WVa6nxZA2GH3pZCrYceZBGjdueWIZB03Sv5ZCDJKF980VZC1QQ5oI1rQAhKWuZBnRK3VwK1ynZBMLcEZAExFXMwTeLzWeHat3htWqfADBvgZDZD';
    $appToken = '277362705469493|P3RAjs5IxaJSGt2tNhBu2f4nRtI';
    $pageId = '276031529726223';
    $igUserId = '49533997525'; // Replace with your Instagram user ID

    // Facebook Graph API Post
    $fbEndpoint = "https://graph.facebook.com/$pageId/photos";
    $fbParams = [
        'url' => $imagePath,
        'caption' => $caption,
        'access_token' => $fbAccessToken,
    ];

    $fbCh = curl_init();
    curl_setopt($fbCh, CURLOPT_URL, $fbEndpoint);
    curl_setopt($fbCh, CURLOPT_POST, true);
    curl_setopt($fbCh, CURLOPT_POSTFIELDS, http_build_query($fbParams));
    curl_setopt($fbCh, CURLOPT_RETURNTRANSFER, true);
    $fbResponse = curl_exec($fbCh);
    curl_close($fbCh);

    // Instagram Graph API Post
    $igEndpoint = "https://graph.instagram.com/$igUserId/media";
    $igParams = [
        'image_url' => $imagePath,
        'caption' => $caption,
        'access_token' => $fbAccessToken, // Using the same access token for Instagram
    ];

    $igCh = curl_init();
    curl_setopt($igCh, CURLOPT_URL, $igEndpoint);
    curl_setopt($igCh, CURLOPT_POST, true);
    curl_setopt($igCh, CURLOPT_POSTFIELDS, http_build_query($igParams));
    curl_setopt($igCh, CURLOPT_RETURNTRANSFER, true);
    $igResponse = curl_exec($igCh);
    curl_close($igCh);

    return ['fb' => $fbResponse, 'ig' => $igResponse];
}

// Usage
$imagePath = 'https://yourdomain.com/path-to-your-image.jpg'; // Replace with your image URL
$caption = 'Herkullinen lounasbuffet on katettu! Tervetuloa! Lounasbuffetimme on täynnä herkullisia vaihtoehtoja -  runsas salaattipöytä ja monipuolinen lounasbuffet, joka sisältää 0,33L virvoitusjuoman, jälkiruoan sekä kahvin/teen. Lounas tarjoillaan arkisin klo 10.00 - 15.00. Voit myös soittaa meille ja varata pöydän etukäteen. Löydät meidät osoitteesta Talvikkitie 38, 01300 Vantaa. Tervetuloa herkuttelemaan kanssamme!';

$response = postToSocialMedia($imagePath, $caption);

echo '<pre>';
print_r($response);
echo '</pre>';
?>
