<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Wael Nasreddine <wael.nasreddine@gmail.com>
 * @author     Wael Nasreddine <wael.nasreddine@gmail.com>
 * @package    Contao Assets
 * @license    LGPL
 * @filesource
 */

// InsertTag
$GLOBALS['TL_HOOKS']['generatePage'][] = array('ContaoAssets', ' addContaoAssets');

// Define a couple of variables
define('TL_CONTAO_ASSETS_PATH', realpath(TL_ROOT . '/resources'));
define('TL_CONTAO_ASSETS_MANIFEST', realpath(TL_ROOT . '/resources/manifest.json'));

echo TL_CONTAO_ASSETS_PATH;
echo TL_CONTAO_ASSETS_MANIFEST;
exit;

?>
