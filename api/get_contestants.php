<?php

require_once("connection.php");

if (isset($category)) {
  $table = ucfirst($category);
} else {
  $table = ucfirst($_POST['category']);
}

$table = str_replace(['\'', '"', ',', ';', '<', '>'], '', $table);

function url_treatment($url)
{
  $url = str_replace(["watch?v=", "shorts/"], "embed/", $url);
  $url = str_replace([".be/"], "be.com/embed/", $url);
  $url = explode("?si=", $url)[0];

  return $url;
}

$filter_string = "";

if (isset($_POST["voter"]) && $_POST["voter"] !== "") {
  $has_voted = $connection->query("SELECT `loser` FROM `votes` v WHERE `voter` = '{$_POST["voter"]}' AND `category` = '{$table}'");


  if ($has_voted->num_rows > 0) {
    $filter = $has_voted->fetch_all(MYSQLI_ASSOC);

    $filter_string .= "AND e.`id` NOT IN (";
    foreach ($filter as $f) {
      $filter_string .= "'{$f['loser']}',";
    }
    $filter_string = rtrim($filter_string, ",");
    $filter_string .= ")";
  }

  $only_one = $connection->query("SELECT e.id, e.category, e.url, c.name FROM ENTRIES e LEFT JOIN `contestants` c ON c.id = e.contestant WHERE `category` = '{$table}' {$filter_string}");

  if ($only_one->num_rows == 1) {
    $rows = $only_one->fetch_all(MYSQLI_ASSOC);

    $url_0 = url_treatment($rows[0]['url']);
    echo <<<EOT
    <current-contestants>
      <h2>{$rows[0]['name']}</h2>
    </current-contestants>

    <entries>
      <item>
        <video-container>
          <iframe src="{$url_0}" frameborder="0" allowfullscreen></iframe>
        </video-container>
        <item-inner>
          <div>
            <img src="/assets/img/logo.webp" alt="">
            <h4>{$rows[0]['name']}</h4>
          </div>
          <input type="radio" name="winner" value="{$rows[0]['id']}"></input>
        </item-inner>
      </item>
    </entries>
    EOT;
    return;
  }
}

$result = $connection->query("SELECT e.id, e.category, e.url, c.name FROM entries e 
  JOIN 
  (SELECT id as entry FROM entries WHERE RAND() < (SELECT ((14 / COUNT(*)) * 10) FROM entries) AND `category` = '{$table}' {$filter_string} ORDER BY RAND() LIMIT 14) AS r ON r.entry = e.id INNER JOIN `contestants` c ON c.id = e.contestant LIMIT 14");

if ($result->num_rows < 2) {
  $result = $connection->query("SELECT e.id, e.category, e.url, c.name FROM entries e 
    JOIN 
    (SELECT id as entry FROM entries WHERE RAND() < (SELECT ((14 / COUNT(*)) * 10) FROM entries) AND `category` = '{$table}' {$filter_string} ORDER BY RAND() LIMIT 14) AS r ON r.entry = e.id INNER JOIN `contestants` c ON c.id = e.contestant LIMIT 14");
}

$rows = $result->fetch_all(MYSQLI_ASSOC);

$url_0 = url_treatment($rows[0]['url']);
$url_1 = url_treatment($rows[1]['url']);

echo <<<EOT
  <current-contestants>
    <div>
      <h2>{$rows[0]['name']}</h2>
    </div>
    <div>X</div>
    <div>
      <h2>{$rows[1]['name']}</h2>
    </div>
  </current-contestants>

  <entries>
    <item>
      <video-container>
        <iframe src="{$url_0}" frameborder="0" allowfullscreen></iframe>
      </video-container>
      <item-inner>
        <div>
          <img src="/assets/img/logo.webp" alt="">
          <h4>{$rows[0]['name']}</h4>
        </div>
        <input type="radio" name="winner" value="{$rows[0]['id']}"></input>
      </item-inner>
    </item>
    <item>
      <video-container>
        <iframe src="{$url_1}" frameborder="0" allowfullscreen></iframe>
      </video-container>
      <item-inner>
        <div>
          <img src="/assets/img/logo.webp" alt="">
          <h4>{$rows[1]['name']}</h4>
        </div>
        <input type="radio" name="winner" value="{$rows[0]['id']}"></input>
      </item-inner>
    </item>
  </entries>
EOT;
