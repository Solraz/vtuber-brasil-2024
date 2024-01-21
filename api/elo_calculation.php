<?php

function elo_calculation($winner, $loser)
{
  $entry_1 = $winner;
  $entry_2 = $loser;
  $k_factor = 32;

  $expected_1 = 1 / (1 + (10 ** (($entry_2 - $entry_1) / 400)));
  $expected_2 = 1 / (1 + (10 ** (($entry_1 - $entry_2) / 400)));

  $expected_1 = number_format($expected_1, 2);
  $expected_2 = number_format($expected_2, 2);

  $ratings = [];
  $ratings["winner"] = $entry_1 + ($k_factor * (1 - $expected_1));
  $ratings["loser"] = $entry_2 + ($k_factor * (0 - $expected_2));

  return $ratings;
}
