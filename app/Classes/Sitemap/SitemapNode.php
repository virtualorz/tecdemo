<?php

namespace App\Classes\Sitemap;

use Config;
use Illuminate\Support\Arr;
use Sitemap as SitemapFacade;

class SitemapNode {

    private $realPath = null;
    private $path = null;
    private $key = null;
    private $node = null;
    private $children = null;
    private $parents = null;

    public function __construct($nodePath) {
        if (empty($nodePath) || !is_string($nodePath)) {
            $this->children = array();
            $this->inherits = array();
            $this->parents = array();
            return;
        }

        // path
        $nodePath = SitemapFacade::normalizePath($nodePath);
        $this->realPath = $nodePath;
        $this->path = $nodePath;

        // node
        $segPath = array_filter(explode('.', $nodePath));
        $segPathLength = count($segPath);
        $tmpNode = Config::get(trim('sitemap.' . $nodePath, '.'), array());
        if (count($tmpNode) <= 0) {
            for ($i = 0; $i < $segPathLength; $i++) {
                $tmpPath = implode('.', array_slice($segPath, 0, $segPathLength - $i)) . '._node.' . implode('.', array_slice($segPath, -$i, $i));
                $tmpPath = trim($tmpPath, '.');
                $tmpNode = Config::get(trim('sitemap.' . $tmpPath, '.'), array());
                if (count($tmpNode) > 0) {
                    $this->realPath = $tmpPath;
                    break;
                }
            }
        }
        $this->key = last($segPath);

        if (isset($tmpNode['_prop'])) {
            $this->node = $tmpNode['_prop'];
        } else if (isset($tmpNode['_node']['_prop'])) {
            $this->realPath = trim($this->realPath . '._node', '.');
            $this->node = $tmpNode['_node']['_prop'];
        } else {
            $this->realPath = null;
            $this->path = null;
            $this->key = null;
            $this->node = null;
            $this->children = array();
            $this->inherits = array();
            $this->parents = array();
            return;
        }
    }

    public function prop($property = null, $default = null) {        
        return $this->isEmpty() ? null : Arr::get($this->node, $property, $default);
    }

    public function hasProp($property = null) {
        return $this->isEmpty() ? false : Arr::has($this->node, $property);
    }

    public function hasChildren($key = null, $filter = array()) {
        $children = $this->getChildren($key, $filter);
        return (is_array($children) && count($children) > 0);
    }

    public function isEmpty() {
        return is_null($this->node);
    }

    public function getChildren($key = null, $filter = array()) {
        if (is_null($this->children)) {
            $this->loadChildren();
        }

        if (is_null($key)) {
            if (count($this->children) <= 0 || count($filter) <= 0) {
                return $this->children;
            } else {
                $children = array_filter($this->children, array(new SitemapNodeFilter($filter), 'filter'));

                return $children;
            }
        } else if (isset($this->children[$key])) {
            if (count($filter) <= 0 || (new SitemapNodeFilter($filter))->filter($this->children[$key]) === true) {
                return $this->children[$key];
            } else {
                return new SitemapNode(null);
            }
        } else {
            return new SitemapNode(null);
        }
    }

    public function getParents($level = null, $filter = array()) {
        if (is_null($this->parents)) {
            $this->loadParents();
        }

        $parents = array_values($this->parents);
        if (is_null($level)) {
            if (count($parents) <= 0 || count($filter) <= 0) {
                return $parents;
            } else {
                $parents = array_filter($parents, array(new SitemapNodeFilter($filter), 'filter'));

                return $parents;
            }
        } else if (isset($parents[$level])) {
            if (count($filter) <= 0 || (new SitemapNodeFilter($filter))->filter($parents[$level]) === true) {
                return $parents[$level];
            } else {
                return new SitemapNode(null);
            }
        } else {
            return new SitemapNode(null);
        }
    }

    public function getParent() {
        if (is_null($this->parents)) {
            $this->loadParents();
        }

        if (count($this->parents) > 0) {
            return last($this->parents);
        } else {
            return new SitemapNode(null);
        }
    }

    public function getPermissionNode() {
        if ($this->prop('permission') != SitemapAccess::INHERIT) {
            return $this;
        } else {
            $tmpPerm = SitemapAccess::INHERIT;
            $tmpNode = last($this->getParents(null, ['permission' => function($k) use($tmpPerm) {
                            return $k != $tmpPerm;
                        }]));
            if ($tmpNode !== false) {
                return $tmpNode;
            } else {
                return $this;
            }
        }
    }

    public function getPathNodes($levelStart = 0, $levelEnd = null) {
        $pathNodes = $this->getParents();
        $pathNodes[] = $this;

        return array_slice($pathNodes, $levelStart, $levelEnd, true);
    }

    public function getRoot() {
        $root = head($this->getPathNodes());
        if ($root === false) {
            $root = new SitemapNode(null);
        }

        return $root;
    }

    public function getPath() {
        return $this->path;
    }

    public function getRealPath() {
        return $this->realPath;
    }

    public function getKey() {
        return $this->key;
    }

    public function getLocalePath() {
        return SitemapFacade::getLocalePath($this->path);
    }

    public function getName($parameters = [], $locale = null) {
        return SitemapFacade::getName($this->path, $parameters, $locale);
    }

    public function getUrl($param = [], $absolute = true) {
        $path = $this->path;
        if (isset($param['optional']) && $this->hasChildren() && !SitemapFacade::isPathHasParam($path)) {
            $path .= '.index';
        }
        return SitemapFacade::getUrl($path, $param, $absolute);
    }

    public function getUrlHasParam($param = [], $absolute = true) {
        $path = trim($this->path . '.index', '.');
        return SitemapFacade::getUrl($path, $param, $absolute);
    }

    ##

    private function loadChildren() {
        $tmpNodeA = Config::get(trim('sitemap.' . $this->realPath, '.'), array());
        $tmpNodeB = array();
        if (ends_with($this->realPath, '_node')) {
            $tmpNodeB = Config::get(trim('sitemap.' . substr($this->realPath, 0, -5), '.'), array());
        }
        $keysA = array_keys($tmpNodeA);
        $keysB = array_keys($tmpNodeB);
        $keys = array_unique(array_merge($keysA, $keysB));

        $children = [];


        foreach ($keys as $k => $v) {
            if (starts_with($v, '_')) {
                continue;
            }
            $tmpNode = new SitemapNode($this->path . '.' . $v);
            if (!$tmpNode->isEmpty()) {
                $children[$v] = $tmpNode;
            }
        }
        $this->children = $children;
    }

    private function loadParents() {
        $segPath = array_filter(explode('.', $this->path));
        $segPath = array_slice($segPath, 0, -1);

        $parents = [];
        $keyPath = '';
        foreach ($segPath as $k => $v) {
            $keyPath = trim($keyPath . '.' . $v, '.');
            $tmpNode = new SitemapNode($keyPath);
            if (!$tmpNode->isEmpty()) {
                $parents[$keyPath] = $tmpNode;
            }
        }

        $this->parents = $parents;
    }

}
