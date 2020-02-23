<?php

define("EXM_ECOM_CLASSES", array(
	"TMA\ExperienceManager\Integration" => "classes/class.integration.php",
	"TMA\ExperienceManager\Plugins" => "includes/class.plugins.php",
	"TMA\ExperienceManager\TMA_Request" => "includes/class.request.php",
));

function exm_ecom_autoload($class_name) {
	if (array_key_exists($class_name, EXM_ECOM_CLASSES)) {
		require_once TMA_EXPERIENCE_MANAGER_DIR . "/" . EXM_ECOM_CLASSES[$class_name];
	}
}

spl_autoload_register('exm_ecom_autoload');
