<?php
if (isset($_GET['help'])) {
	$help = "Usage: wiki [subject]\nSearch for a Wikipedia page.";
} else {
	$_POST['trigger_word'] = 'wiki:';
	$_POST['text'] = $_POST['trigger_word'] . ' ' . $message;
	
	foreach($_POST as $key=>$value) { $_POST_DATA .= $key.'='.$value.'&'; }
	$_POST_DATA = rtrim($_POST_DATA, '&');

	$ch = curl_init('http://tommahieu.eu/slack/hook');
	curl_setopt($ch,CURLOPT_USERAGENT,'Faceless');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch,CURLOPT_POST, count($_POST));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $_POST_DATA);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	echo curl_exec($ch);
	curl_close($ch);
}