<?php

require_once("connection.php");
require_once("elo_calculation.php");

$vote = $_POST['winner'];
$voter = $_POST['voter'];

$vote = explode("/", $vote);

$winner = $vote[0];
$loser = $vote[1];

$result = $connection->query("SELECT id, elo, category, contestant FROM entries e WHERE id IN ('{$vote[0]}', '{$vote[1]}') LIMIT 2");
$rows = $result->fetch_all(MYSQLI_ASSOC);

if ($rows[0]['id'] == $winner) {
  $winner_row = $rows[0];
  $loser_row = $rows[1];
} else {
  $winner_row = $rows[1];
  $loser_row = $rows[0];
}


$vote = $connection->query("INSERT INTO votes (`voter`, `contestant`, `winner`, `loser`, `loser_elo`, `winner_elo`, `category`) VALUES ('{$voter}', '{$winner_row['contestant']}', '{$winner_row['id']}', {$loser_row['id']},'{$loser_row['elo']}', '{$winner_row['elo']}', '{$winner_row['category']}')");

if ($vote) {
  $ratings = elo_calculation($winner_row['elo'], $loser_row['elo']);
  $winner_row['elo'] = $ratings["winner"];
  $loser_row['elo'] = $ratings["loser"];

  $connection->query("UPDATE entries SET elo = '{$winner_row["elo"]}' WHERE id = '{$winner_row['id']}'");
  $connection->query("UPDATE entries SET elo = '{$loser_row["elo"]}' WHERE id = '{$loser_row['id']}'");

  $category = $winner_row['category'];
  include "get_contestants.php";
}
