<?php

namespace App\Helpers;

class Generated {

    public static function color()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);        
    }

    public static function buildTree($elements, $parentId = NULL)
    {
        $tree = [];
        foreach ($elements as $key => $element) {
            if ($element['parent'] == $parentId) {
                $children = self::buildTree($elements, $element['id']);
               
                if ($children) {
                    $element['children'] = $children;
                }

                $tree[] = $element;
            }
        }

        return $tree;
    }

}