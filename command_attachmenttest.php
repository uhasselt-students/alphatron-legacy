<?php

$attachment = new Attachment("AttachmentTest message.", "Dit is een test van de OO Attachment Implementatie.", "#FF0000");
$attachment->createField("Attachment Test", $message, false);
$attachment->createField("Attachment Field 2", $message . $message, false);

$message = new Message("Axel is gay.", $channelname);
$message->addAttachment($attachment);

$attachment = new Attachment("2de attachment message.", "Dit is een tweede test.", "#00FF00");
$attachment ->createField("Attachment Field 3.", "Axel is heel gay.", false);

$message->addAttachment($attachment);

$attachment = new Attachment("3de attachment message.", null, "#0000FF");
$attachment->createField("Attachment Field 4", "Axel is heel heel gay", false);

$message->addAttachment($attachment);

$message->send();


?>