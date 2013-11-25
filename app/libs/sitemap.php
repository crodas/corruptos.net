<?php

class Sitemap implements Iterator
{
    protected $cursor;
    protected $step;

    protected $queue = array();
    protected $current;
    protected $baseUrl;

    public function __construct(Iterator $cursor, Closure $step)
    {
        $this->cursor = $cursor;
        $this->step   = $step;
    }

    public function generate($file, $base = '')
    {
        $this->baseUrl = $base;
        $xml = Service::get('view')->get('sitemap/index', [])->render([
            'urls' => $this,
            'base' => $base
        ], true);
        \crodas\File::write($file, $xml);
    }

    public function current()
    {
        if (empty($this->current)) {
            $step    = $this->step;
            $current = $step($this->cursor->current());
            if (is_array($current)) {
                $this->queue = $current;
                $current = array_shift($this->queue);
            }
        } else {
            $current = $this->current;
        }
        return (object)['url' => $this->baseUrl . $current, 'lastmod' => 0, 'changefreq' => ''];
    }


    public function rewind()
    {
        $this->queue   = array();
        $this->current = null;
        return $this->cursor->rewind();
    }

    public function key()
    {
        return $this->cursor->key();
    }


    public function valid()
    {
        if (!empty($this->current) || !empty($this->queue)) {
            return true;
        }
        return $this->cursor->valid();
    }

    public function next()
    {
        $this->current = NULL;
        if (!empty($this->queue)) {
            $this->current = array_shift($this->queue);
            return;
        }
        $this->cursor->next();
    }
}
