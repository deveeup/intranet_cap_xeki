<?php
require_once dirname(__FILE__) . "/../common/main_for_controllers.php";
\xeki\security::full_session_destroy();

\xeki\core::redirect($base_url);
