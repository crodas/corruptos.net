<?php

use Seld\JsonLint\JsonParser;


class Http
{
    public static function save($path, $content)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, gzencode($content), LOCK_EX);
    }

    public static function wget($url, $ttl = -1, $raw = false, $forceType = '')
    {
        $hash  = sha1($url);
        $cache = __DIR__ . '/../../tmp/http/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4) . '.json.gz';
        if (is_file($cache)) {
            $cached = @json_decode(gzdecode(file_Get_contents($cache)));
            if ($cached->expires <= 0 || $cached->expires > time()) {
                $html = $cached->html;
            }
        } 
        if (empty($html)) {
            echo "wget $url\n";
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERAGENT =>  'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5',
            ]);
            $html = curl_exec($ch);
            $contentType =  $forceType ?: curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_close($ch);

            $bom  = "\xEF\xBB\xBF";
            if (substr($html, 0, 3) === $bom) {
                $html = substr($html, 3);
            }

            if (preg_match('/html/', $contentType)) {
                $html = ForceUTF8\Encoding::toUTF8($html);
                if (is_callable('tidy_repair_string')) {
                    // thanks cardinal for given me a hard time serving broken html
                    $html = tidy_repair_string($html, array('wrap' => 0), 'utf8');
                }
                $html = mb_convert_encoding($html,'HTML-ENTITIES','UTF-8'); 
            }
            if ($contentType == 'json') {
                $parser = new JsonParser();
                $html = $parser->parse($html);
            }
            self::save($cache, json_encode([
                'type'    => $contentType,
                'expires' => $ttl > 0 ? time() + $ttl : $ttl,
                'html'    => $html,
                'url'     => $url
            ]));
        }

        
        if ($raw) {
            return $html;
        }

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
