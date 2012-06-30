<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Wael Nasreddine <wael.nasreddine@gmail.com>
 * @author     Wael Nasreddine <wael.nasreddine@gmail.com>
 * @package    Contao Assets
 * @license    LGPL
 * @filesource
 */

class ContaoAssets extends Backend {

  // The javascript string that will be used by sprintf to add a javascript file
  private $javascript_str = '<script type="text/javascript" src="%s"></script>';

  // The stylesheet string that will be used by sprintf to add a stylesheet file
  private $stylesheet_str = '<link rel="stylesheet" type="text/css" href="%s" />';

  // Internal objects
  private $objPage, $objLayout, $objPageRegular, $manifest;

  public function addContaoAssets($objPage, $objLayout, $objPageRegular) {
    $this->objPage        = $objPage;
    $this->objLayout      = $objLayout;
    $this->objPageRegular = $objPageRegular;

    if($objLayout->enableContaoAssets) {
      $this->loadManifest();
      $this->prependHead($this->stylesheet_str, $this->manifest['application.css']);
      $this->prependHead($this->javascript_str, $this->manifest['application.js']);
    }
  }

  /**
   *
   * This function adds the given asset (Fully qualified HTML)
   * to the TL_HEAD global variable which gets parsed by the PageRegular
   *
   * @param [String] $asset
   */
  private function prependHead($string, $asset) {
    if (!array_key_exists('TL_HEAD', $GLOBALS) || !is_array($GLOBALS['TL_HEAD']))
      $GLOBALS['TL_HEAD'] = array();

    $GLOBALS['TL_HEAD'][] = sprintf($string, $this->assets_url() . '/' . $asset);
  }

  /**
   * This function loads up the manifest and decode into a PHP object
   */
  private function loadManifest() {
    if (empty($this->manifest)) {
      if(!file_exists(TL_CONTAO_ASSETS_MANIFEST)) {
        $this->log("The manifest does not exist, serving application.css and application.js", 'ContaoAssets loadManifest()', TL_WARN);
        $this->manifest = array(
          'application.css' => 'application.css',
          'application.js'  => 'application.js',
        );
      } else {
        $this->manifest = Spyc::YAMLLoad(TL_CONTAO_ASSETS_MANIFEST);
      }
    }
  }

  /**
   * This function returns either TL_CONTAO_ASSETS_PUBLIC_PATH or TL_CONTAO_ASSETS_RAILS_HOST
   */
  private function assets_url() {
    if(!file_exists(TL_CONTAO_ASSETS_MANIFEST))
      return 'http://' . TL_CONTAO_ASSETS_RAILS_HOST . ':' . TL_CONTAO_ASSETS_RAILS_PORT . TL_CONTAO_ASSETS_RAILS_PATH;
    else
      return TL_CONTAO_ASSETS_PUBLIC_PATH;
  }
}

?>
