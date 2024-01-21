<?php

// require_once("connection.php");

// $entries = [];

// $people = file_get_contents('test3.txt');
// $people_array = explode("\n", $people);

// $people_query_format = "";
// for ($i = 0; $i < count($people_array); $i++) {
//   $name = rtrim($people_array[$i], "\r");
//   $people_query_format .= "'{$name}',";

//   if ($i == count($people_array) - 1) $people_query_format = rtrim($people_query_format, ",");
// }

// $people_query = <<<EOT
//   SELECT id, name FROM contestants WHERE name IN ({$people_query_format}) GROUP BY `id`;
// EOT;
// $people_array_to_id = $connection->query($people_query);
// $people_array_to_id = $people_array_to_id->fetch_all(MYSQLI_ASSOC);

// $new_people = [];
// foreach ($people_array_to_id as $p) {
//   $new_people[$p['name']] = $p['id'];
// }

// $videos = file_get_contents('test.txt');
// $videos_array = explode("\n", $videos);

// $categories = file_get_contents('test2.txt');
// $categories_array = explode("\n", $categories);


// for ($i = 0; $i < count($videos_array); $i++) {
//   $entries[$i] = [$new_people[rtrim($people_array[$i], "\r")], rtrim($videos_array[$i], "\r"), rtrim($categories_array[$i], "\r")];
// }

// $query = "";
// for ($i = 0; $i < count($entries); $i++) {
//   $query .= "('{$entries[$i][0]}', '{$entries[$i][1]}', '{$entries[$i][2]}'),";

//   if ($i == count($entries) - 1) $query = rtrim($query, ",");
// }
// $result = $connection->query("INSERT INTO entries (`contestant`, `url`, `category`) VALUES {$query};");

// $contact = file_get_contents('test4.txt');
// $contact_array = explode("\n", $contact);

// for ($i = 0; $i < count($people_array); $i++) {
//   $entries[rtrim($people_array[$i], "\r")] = rtrim($contact_array[$i], "\r");
// }

// $entries_keys = array_keys($entries);
// $entries_values = array_values($entries);

// $query = "";
// for ($i = 0; $i < count($entries_keys); $i++) {
//   $truei = $i + 1;
//   $query .= "('{$entries_keys[$i]}', '{$entries[$entries_keys[$i]]}'),";

//   if ($i == count($entries) - 1) $query = rtrim($query, ",");
// }

// $result = $connection->query("INSERT INTO contestants (`name`, `contact`) VALUES {$query};");
