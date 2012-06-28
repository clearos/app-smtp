<?php

/**
 * SMTP forwarder item view.
 *
 * @category   ClearOS
 * @package    SMTP
 * @subpackage Views
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('network');
$this->lang->load('smtp');

///////////////////////////////////////////////////////////////////////////////
// Form handler
///////////////////////////////////////////////////////////////////////////////

if ($form_type === 'edit') {
	$form_path = '/smtp/forwarding/edit/' . $domain;
	$buttons = array(
		form_submit_update('submit'),
		anchor_cancel('/app/smtp/forwarding/'),
		anchor_delete('/app/smtp/forwarding/delete/' . $domain)
	);
} else {
	$form_path = '/smtp/forwarding/add';
	$buttons = array(
		form_submit_add('submit'),
		anchor_cancel('/app/smtp/forwarding/')
	);
}

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

echo form_open($form_path);
echo form_header(lang('smtp_mail_forwarding'));

echo field_input('domain', $domain, lang('network_domain'));
echo field_input('server', $server, lang('smtp_target_server'));
echo field_input('port', $port, lang('network_port'));
echo field_button_set($buttons);

echo form_footer();
echo form_close();
