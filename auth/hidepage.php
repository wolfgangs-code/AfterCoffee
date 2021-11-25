<?php
if (!defined('AUTH')) {
	http_response_code(404);
	require_once '../config/userset.php';
    include USERSET["system"]["errorPath"]["404"];
    exit;
}
?>