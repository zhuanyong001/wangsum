<?php

namespace App\Dao;

use App\Models\Menu;

class MenuDao
{
    public function getMenus($map = [])
    {
        $menus = Menu::where($map)->whereNull('parent')->orderBy('sort')->get();

        foreach ($menus as $menu) {
            $menu->renderMenu = !!$menu->renderMenu;
            $children = $this->getChildMenus($menu->name, $map);
            if (count($children)) {
                $menu->children = $children;
            }
        }
        return $menus;
    }
    public function getChildMenus($parent, $map = [])
    {
        $menus = Menu::where($map)->where('parent', $parent)->orderBy('sort')->get();

        foreach ($menus as $menu) {
            $menu->renderMenu = !!$menu->renderMenu;
            $children = $this->getChildMenus($menu->name,  $map);
            if (count($children)) {
                $menu->children = $children;
            }
        }
        return $menus;
    }
    public function save($menus)
    {
        Menu::updateOrCreate(['id' => $menus['id'] ?? 0], $menus);
    }

    public function delete($id)
    {
        Menu::destroy($id);
    }
}
