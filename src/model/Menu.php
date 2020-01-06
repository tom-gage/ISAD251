<?php


class Menu
{
    private static $menuItemsArray = array();

    public function __construct($in_menuItemsArray)
    {
        $menuItemsArray = $in_menuItemsArray;
    }

    /**
     * @return array
     */
    public static function getMenuItemsArray()
    {
        return self::$menuItemsArray;
    }

    /**
     * @param array $menuItemsArray
     */
    public static function setMenuItemsArray($menuItemsArray)
    {
        self::$menuItemsArray = $menuItemsArray;
    }
}