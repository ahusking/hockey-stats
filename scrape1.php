<?php

/**
 * Downloads a web page from $url, selects the the element by $id
 * and returns it's xml string representation.
 */
function getElementByIdAsString($url, $id, $pretty = true) {
    $doc = new DOMDocument();
    @$doc->loadHTMLFile($url);

    if(!$doc) {
        throw new Exception("Failed to load $url");
    }

    // Obtain the element
    $element = $doc->getElementById($id);

    if(!$element) {
        throw new Exception("An element with id $id was not found");
    }

    if($pretty) {
        $doc->formatOutput = true;
    }

    // Return the string representation of the element
    return $doc->saveXML($element);
}

// call it:
echo getElementByIdAsString('https://sportsdesq.onesporttechnology.com/15/portal/home/orgId/15', 'competition-list');

?>