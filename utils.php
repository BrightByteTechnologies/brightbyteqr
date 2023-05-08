<?php
$config = file_get_contents('config.json');
$data = json_decode($config, true);

$restaurantId = $data['RESTAURANT']['id'];

if (isset($_GET['functionName'])) {
  $functionName = $_GET['functionName'];

  switch ($functionName) {
    case 'getTables':
      echo getTables();
      break;
    case 'registerQRCode':
      echo registerQRCode();
      break;
    case 'reserveTable':
      echo reserveTable();
      break;

    default:
      # code...
      break;
  }
}

function registerQRCode()
{
  // Sanitize user input
  $token = bin2hex(random_bytes(16)); // 16 bytes = 128 bits
  $tableNo = $_GET['tableNo'];
  global $restaurantId;
  global $data;

  $API_KEY = $data['API'][2]['key'];
  $jsonArray = array(
    'restaurant_id' => $restaurantId,
    'token' => $token,
    'tableNo' => $tableNo
  );

  $jsonData = json_encode($jsonArray);

  $ch = curl_init("api.brightbytetechnologies.de/qrcodes/register");

  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('api-key: ' . $API_KEY, 'Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    return json_encode(array('response' => $error_msg, 'code' => $httpCode));
  } else {
    $url = "http://order.brightbytetechnologies.de/?token=" . $token . "&tableNo=" . $tableNo;
    return json_encode(array('qrcodeurl' => $url, 'code' => $httpCode));
  }
}

function getTables()
{
    global $data;
    global $restaurantId;

    $API_KEY = $data['API'][4]['key'];

    $ch = curl_init("api.brightbytetechnologies.de/tables?restaurant_id=" . $restaurantId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('api-key: ' . $API_KEY));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
      return json_encode(array('response' => $error_msg, 'code' => $httpCode));
    } else {
      return $response;
    }
}

function reserveTable()
{
  global $data;
  global $restaurantId;

  $API_KEY = $data['API'][5]['key'];
  $jsonArray = array(
    'restaurant_id' => $restaurantId,
    'tableNo' => $_GET['tableNo']
  );

  $jsonData = json_encode($jsonArray);

  $ch = curl_init("api.brightbytetechnologies.de/tables/reserve");

  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('api-key: ' . $API_KEY, 'Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    return json_encode(array('response' => $error_msg, 'code' => $httpCode));
  } else {
    return $response;
  }
}

?>