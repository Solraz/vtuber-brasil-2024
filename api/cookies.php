<?php

require_once("connection.php");

if (!isset($_POST['voter_id'])) {
  /*
  * API Key
  */
  $random_hex = openssl_random_pseudo_bytes(16);
  $random_hex[6] = chr(ord($random_hex[6]) & 0x0f | 0x40);
  $random_hex[8] = chr(ord($random_hex[8]) & 0x3f | 0x80);
  $uuid_api = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($random_hex), 4));

  $ip = $_SERVER['REMOTE_ADDR'];
  $expiry = new DateTime("NOW");
  $expiry = $expiry->modify("+1 day");
  $expiry = $expiry->format("Y-m-d H:i:s");

  /*
  * Auth Key
  */
  $date = new DateTime("NOW");
  $date = $date->format("Y-m-d_H:i:s");
  $uuid_auth = str_replace(".", "", $ip) . str_replace(["/", "-"], "", $date);

  $query = "INSERT INTO voters (`api_key`, `auth_key`, `expiry`, `ip`, `last_vote`) VALUES ('{$uuid_api}', '{$uuid_auth}', '{$expiry}', '{$ip}', null)";

  $result = $connection->query($query);

  if ($result) echo json_encode(["uuid" => $uuid_api]);
}
