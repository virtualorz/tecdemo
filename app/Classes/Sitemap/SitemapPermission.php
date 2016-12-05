<?php

namespace App\Classes\Sitemap;

use Sitemap as SitemapFacade;

trait SitemapPermission {

    public function getPermissionAll($rootNode, $maxPermLevel = SitemapAccess::ACCESS_REQUIRED) {
        if (is_string($rootNode)) {
            $rootNode = SitemapFacade::node($rootNode);
        }
        $data = $this->buildPermissionAll($rootNode, $maxPermLevel);

        return $data;
    }

    public function getPermissionTree($rootNode) {
        if (is_string($rootNode)) {
            $rootNode = SitemapFacade::node($rootNode);
        }
        $data = $this->buildPermissionTree($rootNode);

        return $data;
    }

    ##

    private function buildPermissionAll($node, $maxPermLevel) {
        $permission = [];
        if ($node->prop('permission') >= SitemapAccess::ACCESS_REQUIRED && $node->prop('permission') <= $maxPermLevel) {
            $permission[] = $node->getPath();
        }

        $nodeAllChildren = $node->getChildren();
        foreach ($nodeAllChildren as $k => $v) {
            $childrenPerm = $this->buildPermissionAll($v, $maxPermLevel);
            $permission = array_merge($permission, $childrenPerm);
        }


        return $permission;
    }

    private function buildPermissionTree($node) {
        $permission = [];
        if ($node->prop('permission') == SitemapAccess::ACCESS_REQUIRED) {
            $permission[] = $node->getPath();
        }

        $nodeAllChildren = $node->getChildren();
        $children = [];
        foreach ($nodeAllChildren as $k => $v) {
            $tmpNode = $this->buildPermissionTree($v);
            if (!is_null($tmpNode)) {
                $children[] = $tmpNode;
            }
        }
        if (empty($permission) && empty($children)) {
            return null;
        }

        if (!is_null($node->prop('route')) && count($children) > 0 && count(explode('.', $node->getPath())) > 2 && $node->prop('permission') != SitemapAccess::INHERIT) {
            array_unshift($children, [
                'id' => 'jstreeNodePerm_' . str_replace('.', '_', $node->getPath()) . '_index',
                'text' => $node->getName(),
                'path' => $node->getPath(),
                'permission' => $permission,
                'children' => []
            ]);
        }

        $data = [
            'id' => 'jstreeNodePerm_' . str_replace('.', '_', $node->getPath()),
            'text' => $node->getName(),
            'path' => $node->getPath(),
            'permission' => $permission,
            'children' => $children
        ];
        return $data;
    }

}
