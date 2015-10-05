<?php
	Event::listen("form.submitted", function($data){
		// Prep mail and send
	}, 1);