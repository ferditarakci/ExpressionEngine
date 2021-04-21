<?php

/**
 * This source file is part of the open source project
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2021, Packet Tide, LLC (https://www.packettide.com)
 * @license   https://expressionengine.com/license Licensed under Apache License, Version 2.0
 */

namespace ExpressionEngine\Updater\Version_6_1_0;

/**
 * Update
 */
class Updater
{
    public $version_suffix = '';

    /**
     * Do Update
     *
     * @return TRUE
     */
    public function do_update()
    {
        $steps = new \ProgressIterator([
            'removeRteExtension',
        ]);

        foreach ($steps as $k => $v) {
            $this->$v();
        }

        return true;
    }

    private function removeRteExtension()
    {
        ee()->db->where('name', 'Rte')->update('fieldtypes', ['version' => '2.0.1']);

        ee()->db->where('module_name', 'Rte')->update('modules', ['module_version' => '2.0.1']);

        ee()->db->where('class', 'Rte_ext')->delete('extensions');
    }


}

// EOF
