<?php
/**
 * This source file is part of the open source project
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2020, Packet Tide, LLC (https://www.packettide.com)
 * @license   https://expressionengine.com/license Licensed under Apache License, Version 2.0
 */

namespace ExpressionEngine\Addons\Teepee\Model;

use ExpressionEngine\Service\Model\Model;

class Toolset extends Model
{
    protected static $_primary_key = 'toolset_id';
    protected static $_table_name = 'teepee_toolsets';

    protected static $_typed_columns = array(
        'settings' => 'base64Serialized',
    );

    protected $toolset_id;
    protected $toolset_name;
    protected $settings;
}
