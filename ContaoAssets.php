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

class ContaoAssets {

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
    $this->loadManifest();

    if($objLayout->enableContaoAssets) {
      $this->addStylesheets();
      $this->addJavascripts();
    }
  }

  /**
   *
   * This function adds the given asset (Fully qualified HTML)
   * to the TL_HEAD global variable which gets parsed by the PageRegular
   *
   * @param [String] $asset
   */
  private function prependHead($asset) {
    if (!array_key_exists('TL_HEAD', $GLOBALS) || !is_array($GLOBALS['TL_HEAD']))
      $GLOBALS['TL_HEAD'] = array();

    $GLOBALS['TL_HEAD'][] = $asset;
  }

  /**
   * This function loads up the manifest and decode into a PHP object
   */
  private function loadManifest() {
    if (empty($this->manifest)) {
      if(!file_exists(TL_CONTAO_ASSETS_MANIFEST))
        throw new Exception("The manifest does not exist, did you run 'rake assets:precompile'?");

      $this->manifest = json_decode(file_get_contents(TL_CONTAO_ASSETS_MANIFEST));
    }
  }

  /**
   * This function adds stylesheet files into the head
   */
  private function addStylesheets() {

    foreach($this->manifest->stylesheets as $stylesheet) {
      $this->prependHead(sprintf($this->stylesheet_str, TL_CONTAO_ASSETS_PUBLIC_PATH . '/' . $stylesheet));
    }
  }

  /**
   * This function adds javascript files into the head
   */
  private function addJavascripts() {

    foreach($this->manifest->javascripts as $javascript) {
      $this->prependHead(sprintf($this->javascript_str, TL_CONTAO_ASSETS_PUBLIC_PATH . '/' . $javascript));
    }
  }
}

?>
