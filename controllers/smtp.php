<?php

/**
 * SMTP controller.
 *
 * @category   apps
 * @package    smtp
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smtp/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * SMTP controller.
 *
 * @category   apps
 * @package    smtp
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smtp/
 */

class SMTP extends ClearOS_Controller
{
	/**
	 * SMTP server overview.
	 */

	function index()
	{
		// Load libraries
		//---------------

		$this->lang->load('smtp');

		// Load views
		//-----------

        $views = array('smtp/server', 'smtp/settings', 'smtp/trusted', 'smtp/forwarding');

        if (clearos_app_installed('accounts')) {
            $this->load->module('accounts/status');

            if ($this->status->unhappy())
                array_unshift($views, 'smtp/accounts_warning');
            else
                $views = array('smtp/server', 'smtp/settings', 'smtp/user_policies', 'smtp/domains', 'smtp/trusted', 'smtp/forwarding');
        }

        $this->page->view_forms($views, lang('smtp_smtp_server'));
	}
}
