<?php

/**
 * SMTP general settings view.
 *
 * @category   ClearOS
 * @package    SMTP
 * @subpackage Views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('smtp');
$this->lang->load('base');

///////////////////////////////////////////////////////////////////////////////
// Form handler
///////////////////////////////////////////////////////////////////////////////

if ($form_type === 'edit') {
    $read_only = FALSE;
    $buttons = array(
        form_submit_update('submit'),
        anchor_cancel('/app/smtp')
    );
} else {
    $read_only = TRUE;
    $buttons = array(anchor_edit('/app/smtp/user_policies/edit'));
}

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

echo form_open('/smtp/user_policies/edit');
echo form_header(lang('smtp_user_policies'));

echo field_toggle_enable_disable('smtp_authentication', $smtp_authentication, lang('smtp_smtp_authentication'), $read_only);
echo field_toggle_enable_disable('smtp_block_plaintext', $smtp_block_plaintext, lang('smtp_block_plaintext'), $read_only);

/* TODO... maybe
if (empty($catch_alls))
    echo field_view(lang('smtp_catch_all'), lang('smtp_no_smtp_users_defined'));
else
    echo field_simple_dropdown('catch_all', $catch_alls, $catch_all, lang('smtp_catch_all'), $read_only);
*/

echo field_button_set($buttons);

echo form_footer();
echo form_close();
