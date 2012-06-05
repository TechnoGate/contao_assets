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

  // The IE specific string
  private $internet_explorer_str = '<!--[if IE%s]>%s<![endif]-->';

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
      if(!file_exists(TL_CONTAO_ASSETS_MANIFEST)) {
        $this->log("The manifest does not exist, did you run 'bundle exec rake assets:precompile'?", 'ContaoAssets loadManifest()', TL_ERROR);
        throw new Exception("The manifest does not exist, did you run 'bundle exec rake assets:precompile'?");
      }

      $this->manifest = json_decode(file_get_contents(TL_CONTAO_ASSETS_MANIFEST));
    }
  }

  /**
   * This function adds stylesheet files into the head
   */
  private function addStylesheets() {

    foreach($this->manifest->stylesheets as $stylesheet) {
      $asset = sprintf($this->stylesheet_str, TL_CONTAO_ASSETS_PUBLIC_PATH . '/' . $stylesheet);

      if(preg_match('/ie([0-9]*).*\.css/', basename($stylesheet), $matches)) {
        if($matches[1] > 0)
          $asset = sprintf($this->internet_explorer_str, ' ' . $matches[1], $asset);
        else
          $asset = sprintf($this->internet_explorer_str, '', $asset);
      }

      $this->prependHead($asset);
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
