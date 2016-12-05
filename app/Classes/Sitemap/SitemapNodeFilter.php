<?php

namespace App\Classes\Sitemap;

class SitemapNodeFilter {

    private $filterRule = array();

    public function __construct($rule) {
        $this->filterRule = $rule;
    }

    public function filter($node) {
        $valid = true;
        foreach ($this->filterRule as $k => $v) {
            if (is_callable($v)) {
                if ($v($node->prop($k)) === false) {
                    $valid = false;
                    break;
                }
            } else {
                if ($node->prop($k) != $v) {
                    $valid = false;
                    break;
                }
            }
        }
        return $valid;
    }

}
