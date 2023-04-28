<?php
  // Sanitize user input
  $token = bin2hex(random_bytes(16)); // 16 bytes = 128 bits
  $tableNo = $_GET['tableNo'];

  $config = file_get_contents('config.json');
  $data = json_decode($config, true);

  $API_KEY = $data['API'][1]['key'];
  $restaurantId = $data['RESTAURANT']['id'];

  $data = array(
      'restaurant_id' => $restaurantId,
      'token' => $token,
      'tableNo' => $tableNo
  );
  
  $jsonData = json_encode($data);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://localhost:3000/qrcodes/register');
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('api-key: ' . $API_KEY, 'Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo json_encode(array('response' => $error_msg, 'code' => $httpCode));
  } else {
    $url = "http://order.brightbytetechnologies.de/?token=" . urlencode($token) . "&tableNo=" . urlencode($tableNo);
    echo json_encode(array('qrcodeurl' => $url, 'code' => $httpCode));
  }
  curl_close($ch);    
?>
