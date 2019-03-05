<?php
/**
 * User: vhnvn
 * Date: 3/26/18
 * Time: 7:30 PM
 */

namespace VZT\Laravel\VZTLog\Model;


class VZTLogContextBuilder
{

    private $context;
    private $callback;

    function __construct($context, $callback)
    {
        $this->context = $context;
        $this->callback = $callback;
    }

    /**
     * @param string|string[] $tag
     *
     * @return $this
     */
    public function withTag($tag)
    {
        if (!is_array($tag)) $tag = [$tag];
        if (!array_key_exists('tags', $this->context)) $this->context['tags'] = [];
        $this->context['tags'] = array_unique(array_merge($this->context['tags'], $tag));
        return $this;
    }

    public function withException($ex)
    {
        $this->context['exception'] = $ex;
        return $this;
    }

    public function withHTMLDoc($name, $content)
    {
        if (!ends_with($name, '.html')) {
            $name .= '.html';
        }

        $this->context['files'][$name] = $content;
        return $this;
    }

    function __destruct()
    {
        ($this->callback)($this->context);
    }
}