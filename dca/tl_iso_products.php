<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Ruud Walraven 2010
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @license    LGPL
 */

/**
 * Table tl_iso_products
 */
$GLOBALS['TL_DCA']['tl_iso_products']['config']['onload_callback'][] = array('tl_iso_products_available_option', 'insertOptionsChoice');


class tl_iso_products_available_option extends Backend
{

	public function __construct()
	{
		parent::__construct();

		$this->import('BackendUser', 'User');
		$this->import('Isotope');
	}


	/**
	 * Build palette for the current product type / variant
	 */
	public function insertOptionsChoice($dc)
	{
		foreach($GLOBALS['TL_DCA']['tl_iso_products']['fields'] as $key => $field)
		{
			if ($field['attributes']['choose_options'])
			{
				$GLOBALS['TL_DCA']['tl_iso_products']['fields'][$key . '__avaopts'] = array
				(			
					'label'                   => array($GLOBALS['TL_DCA']['tl_iso_products']['fields'][$key]['label'][0] . $GLOBALS['TL_LANG']['tl_iso_products']['_available_options']),
					'inputType'               => 'select',
					'options'                 => $GLOBALS['TL_DCA']['tl_iso_products']['fields'][$key]['options'],
					'eval'                    => array('multiple'=>true, 'size'=>5),
				);

				if (is_array($GLOBALS['TL_DCA']['tl_iso_products']['palettes']))
				{
					foreach ($GLOBALS['TL_DCA']['tl_iso_products']['palettes'] as $k => $palette)
					{
						switch ($k)
						{
							case '__selector__':
								continue;
							default:
								if (preg_match('/,'.$key.'[;,]/', $palette))
								{
									$GLOBALS['TL_DCA']['tl_iso_products']['palettes'][$k] = str_replace(array(','.$key.',',','.$key.';'), array(','.$key.','.$key.'__avaopts,', ','.$key.','.$key.'__avaopts;'), $palette);
								}
								else
								{
									$GLOBALS['TL_DCA']['tl_iso_products']['palettes'][$k] = str_replace(';{media_legend}', ','.$key.'__avaopts;{media_legend}', $palette);
								}
						}
					}
				}
			}
		}
	}
}