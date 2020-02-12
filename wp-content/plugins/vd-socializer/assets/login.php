<?php

function callApi(){
	do_action('socializer_facebook');
}
add_action('login_init', 'callApi');
