<?php

$page = explode("/", $_REQUEST['page']);

if (file_exists("{$page[0]}.html")) {
  include "{$page[0]}.html";
} else if (file_exists("{$page[0]}.php")) {
  include "{$page[0]}.php";
} else {
  include "404.html";
}
