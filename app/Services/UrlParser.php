<?php

namespace App\Services;

trait UrlParser
{
    /**
     * Parse url with injecting variable to {variable}
     *
     * @param string $url
     * @param array $vars
     * @return string
     */
    protected function parseUrl($url, array $vars)
    {
        foreach ($vars as $key => $value) {
            $url = str_replace('{'.$key.'}', $value, $url);
        }
        return $url;
    }
}
