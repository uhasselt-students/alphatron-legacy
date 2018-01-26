<?PHP

$author = "BertP";
$timeStart = microtime(true);

if (isset($_GET['help'])) {
    $help = "Usage: porn subj [-unfurl] -> Returns a porn video about 'subj'.\nUse 'gay' or 'tranny' in the subj for more specific types of porn.\n-unfurl makes the thumbnail image expand.\nTranny is a special kind of people :wink:";
} else {
    require('phpQuery/phpQuery/phpQuery.php');

    // Zoekdomein
    $domain = "http://www.pornmd.com/";
    
    /*
    Channel abuse prevention
    */
    if($channelname != "nsfw" && $channelname != "chickas")
    {
        $retArray = array(
            'text' => "Helaas mag ik hier geen dergelijke video's posten :("
        );
        
        echo json_encode($retArray);
        exit;
    }
    
    $searchterm = strtolower($message);
    
    /* Verwijder alle commands */
    $searchterm = str_replace("gay", "", $searchterm);
    $searchterm = str_replace("tranny", "", $searchterm);
    $searchterm = str_replace("-unfurl", "", $searchterm);
    $searchterm = str_replace("-test", "", $searchterm);
    
    // Standaar zoek string
    $search = $domain."straight/";
    
    /*
    Filter voorkeur
    */
    if(strpos($message, "gay") !== false)
        $search = $domain."gay/";
    else if(strpos($message, "tranny") !== false)
        $search = $domain."tranny/";
    else
        ; // Standaard zoeken

    $link = $search . urlencode($searchterm);

    $xml = getXML($link);
    //echo $xml;

    $doc = makeDoc($xml);

    //var_dump($doc);

     /* enige randomness */
    $nummer = rand(0, 10);
    
    $cont = $doc["ul#list-1 li:nth-child($nummer)"];

    //var_dump($cont);
    
    $linkitem = $cont->find("h2.title-video");
    $linkitem = $linkitem->find("a");

    $ladder = $linkitem->attr("href");
    
    $videolink = $domain . $ladder;
    
    /*
    Extract foto
    */
    
    $fotolink = '';
    
    /* Zoek de parameter unfurl */
    if(strpos($message, '-unfurl') !== false)
    {    
        $fotoitem = $cont->find("img.lazy");
        $fotolink = $fotoitem->attr("data-original");
    }
    
    
    /* Stop indien niets gevonden */
    if(empty($ladder) || is_null($ladder))
    {
        $retArray = array(
                'text' => "Geen video gevonden voor $searchterm :confounded:"
        );

        echo json_encode($retArray);
        exit;
    }
    
    
    $timeStop = microtime(true);
    
    // JSON manipulatie

    /* FUCKING HELL!!!! */
    if(strpos($message, "-test") === false)
    {
        $retArray = array(
            'text' => "Video over: ".$searchterm."\n".$fotolink,
            "unfurl_media" => true,
            
            'attachments' =>
                array (
                    // ARRAY OF ARRAYS
                    array(
                        "fallback" => "Video",
                        "text" => "", // Optional text that should appear within the attachment
                        "pretext" => "", // Optional text that should appear above the formatted data
                        "color" => "warning", // Can either be one of 'good', 'warning', 'danger', or any hex color code

                        // Fields are displayed in a table on the message
                        "fields" => array(
                            // ARRAYCEPTION ALWEER
                            array(
                                "title" => "Link", // The title may not contain markup and will be escaped for you
                                "value" => $videolink,
                                "short" => false // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            )
                        )
                    )                    
                )
        );
    }
    else
    {

        // Update array syntax
        $retArray = array(
            'text' => "Video over: ".$searchterm."\n".$fotolink,
            "unfurl_media" => true,

            'attachments' =>
                array (
                    // ARRAY OF ARRAYS
                    array(
                        "fallback" => "DEBUGGING PORN BOT OUTPUT",
                        "text" => "DEBUGGING PORN BOT OUTPUT", // Optional text that should appear within the attachment
                        "pretext" => "", // Optional text that should appear above the formatted data
                        "color" => "good", // Can either be one of 'good', 'warning', 'danger', or any hex color code

                        // Fields are displayed in a table on the message
                        "fields" => array(
                            // ARRAYCEPTION ALWEER
                            array(
                                "title" => "keyword", // The title may not contain markup and will be escaped for you
                                "value" => $searchterm,
                                "short" => true // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            ),
                            
                            array(
                                "title" => "source", // The title may not contain markup and will be escaped for you
                                "value" => $domain,
                                "short" => true // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            ),

                            array(
                                "title" => "searchlocation", // The title may not contain markup and will be escaped for you
                                "value" => $search,
                                "short" => true // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            ),


                            array(
                                "title" => "returned link", // The title may not contain markup and will be escaped for you
                                "value" => $videolink,
                                "short" => false // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            )
                        )
                    ),
                    array(
                        "fallback" => "Server details",
                        "text" => "Server details", // Optional text that should appear within the attachment
                        "pretext" => "", // Optional text that should appear above the formatted data
                        "color" => "danger", // Can either be one of 'good', 'warning', 'danger', or any hex color code

                        // Fields are displayed in a table on the message
                        "fields" => array(
                            // ARRAYCEPTION ALWEER
                            array(
                                "title" => "timing", // The title may not contain markup and will be escaped for you
                                "value" => ($timeStop - $timeStart)." seconden",
                                "short" => true // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            )

                            /*
                            array(
                                "title" => "searchlocation", // The title may not contain markup and will be escaped for you
                                "value" => $search,
                                "short" => true // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            ),


                            array(
                                "title" => "returned link", // The title may not contain markup and will be escaped for you
                                "value" => $videolink,
                                "short" => false // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
                            ),
                            */

                        )
                    )
                    
                )

        );
    }

    // Output json
    // PARAM: JSON_PRETTY_PRINT is slechts beschikbaar vanaf 5.4
    echo json_encode($retArray);
}


function getXML($link) {
    $xml = file_get_contents($link, 0, NULL);

    // CURL fallback
    if($xml === false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $link);
        // Return inhoud op execute
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // geen HTTP headers in output
        curl_setopt($curl, CURLOPT_HEADER, false);
        // Volg redirects
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

        $xml = curl_exec($curl);
        curl_close($curl);
    }

    return $xml;
}

function makeDoc($xml) {
    return phpQuery::newDocument($xml);
}

?>