<?php 

namespace App\Libraries;

class DynamicMenu
{

    public function __construct()
    {
    }

    private static function GetDataItems($data, $group){
        $items = array();
        $label = '';
        $row = 0;
        foreach ($data as $item) {
            if($group == $item->grupo && $label != $item->funcion){
                $items[] = array(
                                    'view'=> $item->vista,
                                    'title'=> $item->funcion
                                );
                $label = $item->funcion;
            $row = $row + 1;
            }
        }
        return $items;
    }
    private static function GetDataGroup($data, $option){
        $groups = array();
        $label = '';
        $row = 0;
        foreach ($data as $group) {
            if($option == $group->division && $label != $group->grupo){
                $groups[] = array(
                                    'title'=> $group->grupo,
                                    'items'=> self::GetDataItems($data, $group->grupo)
                                );
                $label = $group->grupo;
            $row = $row + 1;
            }
        }
        return $groups;
    }

    public static function GetDataOptions($data){
        $options = array();
        $label = '';
        $row = 0;
        foreach ($data as $option) {
            if($label != $option->division){
                $options[] = array(
                                    'title'=> $option->division,
                                    'icon' => $option->icono,
                                    //'group'=> array()
                                    'groups'=> self::GetDataGroup($data, $option->division)
                                );
                $label = $option->division;
            $row = $row + 1;
            }
        }
        $label = '';
        return $options;
    }
}