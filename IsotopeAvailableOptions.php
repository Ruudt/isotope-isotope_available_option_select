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

class IsotopeAvailableOptions extends Frontend
{
	/**
	 * Add custom javascript
	 */
	protected function __construct()
	{
		parent::__construct();
	}
	
	public function adjustOptions($arrAttributes, $arrVariantAttributes, IsotopeProduct $objProduct)
	{
		foreach ($arrAttributes as $attribute)
		{
			if ($GLOBALS['TL_DCA']['tl_iso_products']['fields'][$attribute]['attributes']['choose_options']  )
			{
				$attributeAvailableOptions = $attribute . '__avaopts';
				$attributeAvailableOptions = $objProduct->$attributeAvailableOptions;
				// Assuming options need to be changed whenever
				//  - available options have been chosen
				if (is_array($attributeAvailableOptions) && count($attributeAvailableOptions))
				{
					// traversing $attributeAvailableOptions because it would be the smaller array
					$newOptions = array();
					foreach($attributeAvailableOptions as $key)
					{
						$newOptions[$key] = $GLOBALS['TL_DCA']['tl_iso_products']['fields'][$attribute]['options'][$key];
					}
					
					// "No options" is not an option, so any failure prevents from changing the options
					if (count($newOptions))
					{
						$GLOBALS['TL_DCA']['tl_iso_products']['fields'][$attribute]['options'] = $newOptions;
					}
				}
			}
		}
	}



	/**
	 * Add the product attributes to the db updater array so the users don't delete them while updating
	 * @param array
	 * @return array
	 */
	public function addAttributesToDBUpdate($arrData)
	{
		$objAttributes = $this->Database->execute("SELECT * FROM tl_iso_attributes");

		while ($objAttributes->next())
		{
			if ($objAttributes->type == '' || $GLOBALS['ISO_ATTR'][$objAttributes->type]['sql'] == '' || !strlen($objAttributes->field_name))
			{
				continue;
			}

			$strType = 'select';
			$arrData['tl_iso_products']['TABLE_FIELDS'][$objAttributes->field_name . '__avaopts'] = sprintf('`%s` %s', $objAttributes->field_name . '__avaopts', $GLOBALS['ISO_ATTR'][$strType]['sql']);

			// also check indexes
			if ($objAttributes->fe_filter && $GLOBALS['ISO_ATTR'][$strType]['useIndex'])
			{
				$arrData['tl_iso_products']['TABLE_CREATE_DEFINITIONS'][$objAttributes->field_name . '__avaopts'] = sprintf('KEY `%s` (`%s`)', $objAttributes->field_name . '__avaopts', $objAttributes->field_name . '__avaopts');
			}
		}

		return $arrData;
	}
}
