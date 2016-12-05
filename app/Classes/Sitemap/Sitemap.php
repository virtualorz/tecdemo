<?php

namespace App\Classes\Sitemap;

use Route;
use Request;
use Config;

class Sitemap {

    use SitemapPermission;

    private $currentNode = null;

    public function node($path = null) {
        if (is_null($path)) {
            return $this->getCurrentNode();
        } else {
            return new SitemapNode($path);
        }
    }

    public function getCurrentNode() {
        if (is_null($this->currentNode)) {
            $this->currentNode = new SitemapNode(Route::currentRouteName());
        }

        return $this->currentNode;
    }

    public function route($rootPath) {
        $node = $this->node($rootPath);
        $this->routeGroup($node);
    }

    public function routeWithLocale($rootPath) {
        $node = $this->node($rootPath);
        $this->routeGroup($node);
        $this->routeGroupWithLocale($node);
    }

    public function getLocalePath($path) {
        $path = $this->normalizePath($path);
        $localePathArr = array_filter([$path, '_name']);
        $localePath = implode('.', $localePathArr);

        return 'sitemap.' . $localePath;
    }

    public function getName($path, $parameters = [], $locale = null) {
        return trans($this->getLocalePath($path), $parameters, 'messages', $locale);
    }

    public function getUrl($path, $param = [], $absolute = true) {
        if (is_null(Route::getRoutes()->getByName($path))) {
            return null;
        }

        if (isset($param['optional']) && is_array($param['optional'])) {
            $param['optional'] = $this->formatOptionalParam($param['optional']);
        } else {
            unset($param['optional']);
        }

        $routeLocale = Route::input('locale');
        if (is_null($routeLocale)) {
            return route($path, $param, $absolute);
        } else {
            $routePath = trim('{locale}.' . $path, '.');
            $param['locale'] = $routeLocale;

            return route($routePath, $param, $absolute);
        }
    }

    public function getCurrentUrlWithLocal($locale) {
        $routeName = Route::currentRouteName();
        if (!$this->isPathHasLocale($routeName)) {
            $routeName = trim('{locale}.' . $routeName, '.');
        }

        $param = array_merge(Route::current()->parameters(), ['locale' => $locale]);
        if (isset($param['optional'])) {
            $param['optional'] = $this->formatOptionalParam($param['optional']);
        }

        $qs = Request::getQueryString();
        $url = route($routeName, $param);
        return $qs ? $url . '?' . $qs : $url;
    }

    public function isPathHasLocale($path) {
        return starts_with($path, '{locale}');
    }

    public function isPathHasParam($path) {
        return ends_with($path, 'index');
    }

    public function normalizePath($path) {
        if ($this->isPathHasLocale($path)) {
            $path = substr($path, 8);
        }
        if ($this->isPathHasParam($path)) {
            $path = substr($path, 0, -5);
        }
        return trim($path, '.');
    }

    public function formatOptionalParam($optional) {
        if (count($optional) <= 0) {
            return '';
        }
        $tmpOptional = [];
        foreach ($optional as $k => $v) {
            $tmpOptional[] = $k . '-' . $v;
        }
        return implode('/', $tmpOptional);
    }

    ##

    private function routeGroup(SitemapNode $node, $hasLocale = false) {
        $parent = $node->getParent();
        $isHasGroup = $node->hasChildren();
        $pathKey = $node->getKey();
        if ($isHasGroup) {
            $group = $node->prop('route.group', []);
            $group['prefix'] = $pathKey;
            $group['as'] = '.' . $pathKey;
            if ($parent->isEmpty()) {
                $group['as'] = $pathKey;
                if ($pathKey == Config::get('sitemap.config.main')) {
                    unset($group['prefix']);
                }
            }

            Route::group($group, function() use($node, $hasLocale) {
                $this->routeRoute($node, $hasLocale);
            });
        } else {
            $this->routeRoute($node, $hasLocale);
        }
    }

    private function routeGroupWithLocale(SitemapNode $node) {
        $parent = $node->getParent();
        $isHasGroup = $node->hasChildren();
        $pathKey = $node->getKey();
        if ($isHasGroup) {
            $group = $node->prop('route.group', []);
            $group['prefix'] = '{locale}/' . $pathKey;
            $group['as'] = '.{locale}.' . $pathKey;
            if ($parent->isEmpty()) {
                $group['as'] = '{locale}.' . $pathKey;
                if ($pathKey == Config::get('sitemap.config.main')) {
                    $group['prefix'] = '{locale}';
                }
            }
            Route::group($group, function() use($node) {
                $this->routeRoute($node, true);
            });
        } else {
            $group = $node->prop('route.group', []);
            $group['prefix'] = '{locale}';
            $group['as'] = '{locale}';
            Route::group($group, function() use($node) {
                $this->routeRoute($node, true);
            });
        }
    }

    private function routeRoute(SitemapNode $node, $hasLocale = false) {
        if ($node->hasProp('route')) {
            $parent = $node->getParent();
            $isHasGroup = $node->hasChildren();
            $pathKey = $node->getKey();
            $method = $node->prop('route.method', 'get');
            if(!is_array($method)){
                $method = [$method];
            }

            $param = ltrim($node->prop('route.param', ''), '/');
            $paramSeg = array_filter(explode('/', $param));
            $paramCnt = 0;
            $paramUrl = '';
            $hasOptional = false;
            foreach ($paramSeg as $k => $v) {
                if ($v == '{optional?}') {
                    $paramUrl .= $v . '/';
                    $hasOptional = true;
                    $paramCnt++;
                    break;
                } else {
                    $tmpName = str_replace(['{', '}', '?'], '', $v);
                    $paramUrl .= ($tmpName . '-{' . $tmpName . '}/');
                    $paramCnt++;
                }
            }

            $attr = $node->prop('route.attr', []);
            if ($isHasGroup) {
                $attr['as'] = '';
                $as2 = '.index';
                if ($parent->isEmpty() && $pathKey == Config::get('sitemap.config.main') && !$hasLocale) {
                    $as2 = 'index';
                }

                if ($hasOptional && $paramCnt == 1) {
                    Route::match($method, '', $attr);

                    $attr['as'] = $as2;
                    Route::match($method, 'index/' . $paramUrl, $attr);
                    //Route::$method($paramUrl, $attr);
                } else {
                    Route::match($method, $paramUrl, $attr);
                }
            } else {
                $attr['as'] = '.' . $pathKey;
                $as2 = $attr['as'] . '.index';
                $path = $pathKey;
                if ($parent->isEmpty()) {
                    if (!$hasLocale) {
                        $attr['as'] = $pathKey;
                        $as2 = $attr['as'] . '.index';
                    }
                    if ($pathKey == Config::get('sitemap.config.main')) {
                        $path = '';
                        $attr['as'] = '';
                        $as2 = '.index';
                        if (!$hasLocale) {
                            $as2 = 'index';
                        }
                    }
                }

                if ($hasOptional && $paramCnt == 1) {
                    Route::match($method, $path, $attr);

                    $attr['as'] = $as2;
                    //Route::$method($path . '/index/' . $paramUrl, $attr);
                    Route::match($method, $path . '/' . $paramUrl, $attr);
                } else {
                    Route::match($method, $path . '/' . $paramUrl, $attr);
                }
            }
        }

        foreach ($node->getChildren() as $k => $v) {
            $this->routeGroup($v, $hasLocale);
        }
    }

}
