<?php

function exm_ecom_get_userid () {
	return \TMA\ExperienceManager\TMA_Request::getUserID();
}

function exm_ecom_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $plugin );
}