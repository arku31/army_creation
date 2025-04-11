<?php
require "vendor/autoload.php";

$cli = new AG\Presentation\CLI\GenerateArmy();
$cli->run($argv);