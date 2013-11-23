<?php

class Http
{
    public static function wget($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT =>  'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5',
        ]);
        $html = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        $html = ForceUTF8\Encoding::toUTF8($html);
        $bom  = "\xEF\xBB\xBF";
        if (substr($html, 0, 3) === $bom) {
            $html = substr($html, 3);
        }

        if (is_callable('tidy_repair_string')) {
            // thanks cardinal for given me a hard time serving broken html
            $html = tidy_repair_string($html, array('wrap' => 0), 'utf8');
        }
        $html = mb_convert_encoding($html,'HTML-ENTITIES','UTF-8'); 
        $dom  = new \DomDocument;
        @$dom->loadHTML($html);
        return new \DOMXPath($dom);
    }

    public static function text($object)
    {
        $text = [];
        if (!is_array($object) && !$object instanceof Traversable) {
            $object = [$object];
        }
        foreach ($object as $node) {
            $text[] = trim($node->textContent);
        }

        return ForceUTF8\Encoding::toUTF8(trim(implode("\n", $text)));
    }

}
