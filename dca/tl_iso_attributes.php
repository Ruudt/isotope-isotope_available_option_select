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

// Add callbacks
// our column never changes type: $GLOBALS['TL_DCA']['tl_iso_attributes']['config']['onsubmit_callback'][] = array('tl_iso_attributes_available_options', 'modifyColumn');
$GLOBALS['TL_DCA']['tl_iso_attributes']['config']['ondelete_callback'][] = array('tl_iso_attributes_available_options', 'deleteAttribute');

/**
 * Palette changes
 * Adds choose_options to all attributes that have an foreignKey field
 */
foreach ($GLOBALS['TL_DCA']['tl_iso_attributes']['palettes'] as $key => $palette)
{
	switch ($key)
	{
		case '__selector__':
		case 'selectvariant_option':
		case 'radiovariant_option':
		case 'conditionalselect':
			continue;

		default:
			$GLOBALS['TL_DCA']['tl_iso_attributes']['palettes'][$key] = str_replace(array(',foreignKey,',',foreignKey;'), array(',foreignKey,choose_options,',',foreignKey,choose_options;'), $GLOBALS['TL_DCA']['tl_iso_attributes']['palettes'][$key]);
	}
}

// Fields
$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['field_name']['load_callback'][] = array('tl_iso_attributes_available_options', 'createColumn');
$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['field_name']['save_callback'][] = array('tl_iso_attributes_available_options', 'createColumn');
		
$GLOBALS['TL_DCA']['tl_iso_attributes']['fields']['choose_options'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_attributes']['choose_options'],
	'exclude'				=> true,
	'inputType'				=> 'checkbox',
);

class tl_iso_attributes_available_options extends Backend
{
	public function deleteAttribute($dc)
	{
		if ($dc->id)
		{
			$objAttribute = $this->Database->execute("SELECT * FROM tl_iso_attributes WHERE id={$dc->id}");
		}
	}

	public function createColumn($varValue, $dc)
	{
		$varValue = standardize($varValue, true);

		if (in_array($varValue, array('id', 'pid', 'sorting', 'tstamp')))
		{
			// @todo Do I also need to throw an exception?
			throw new Exception($GLOBALS['TL_LANG']['ERR']['systemColumn'], $varValue);
			return '';
		}

		if (strlen($varValue) && !$this->Database->fieldExists($varValue . '__avaopts', 'tl_iso_products'))
		{
			$strType = 'select';

			$this->Database->query(sprintf("ALTER TABLE tl_iso_products ADD %s %s", $varValue . '__avaopts', $GLOBALS['ISO_ATTR'][$strType]['sql']));
		}

		return $varValue;
	}
}