<?php

namespace App\DataTables;

trait DataTableTrait
{
    public function badge($text, $type, $margin = 0)
    {
        return '<span class="badge badge-dim badge-pill badge-' . $type . ' ml-' . $margin . '">' . __($text) . '</span>';
    }

    public function button($route, $param, $type, $title, $icon, $name = '', $target = '_self')
    {
        return '<a 
                    title="' . $title . '" 
                    data-name="' . $name . '" 
                    href="' . route($route, $param) . '" 
                    class="btn btn-' . $type . '" 
                    target="' . $target . '">
                    <em class="icon ni ni-' . $icon . '"></em>
                </a>';
    }
}