<?php

/**
 * User policies controller.
 *
 * @category   Apps
 * @package    SMTP
 * @subpackage Controllers
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
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * User policies controller.
 *
 * @category   Apps
 * @package    SMTP
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smtp/
 */

class User_Policies extends ClearOS_Controller
{
    /**
     * Settings default controller.
     *
     * @return view
     */

    function index()
    {
        $this->view();
    }

    /**
     * Edit view.
     *
     * @return view
     */

    function edit()
    {
        $this->_item('edit');
    }

    /**
     * View view.
     *
     * @return view
     */

    function view()
    {
        $this->_item('view');
    }

    /**
     * Common view/edit view.
     *
     * @param string $form_type form type
     *
     * @return view
     */

    function _item($form_type)
    {
        // Load libraries
        //---------------

        $this->lang->load('smtp');
        $this->load->library('smtp/Postfix');

        // Set validation rules
        //---------------------
         
        $this->form_validation->set_policy('smtp_authentication', 'smtp/Postfix', 'validate_smtp_authentication_state', TRUE);
        $this->form_validation->set_policy('smtp_block_plaintext', 'smtp/Postfix', 'validate_smtp_block_plaintext', TRUE);
        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if (($this->input->post('submit') && $form_ok)) {
            try {
                $this->postfix->set_smtp_authentication_state($this->input->post('smtp_authentication'));
                $this->postfix->set_smtp_block_plaintext($this->input->post('smtp_block_plaintext'));

                $this->postfix->reset();

                $this->page->set_status_updated();
                redirect('/smtp/user_policies');
            } catch (Engine_Exception $e) {
                $this->page->view_exception($e->get_message());
                return;
            }
        }

        // Load view data
        //---------------

        try {
            $data['form_type'] = $form_type;
            $data['catch_all'] = $this->postfix->get_catch_all();
            $data['catch_alls'] = $this->postfix->get_catch_all_users();
            $data['smtp_authentication'] = $this->postfix->get_smtp_authentication_state();
            $data['smtp_block_plaintext'] = $this->postfix->get_smtp_block_plaintext();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('smtp/user_policies', $data, lang('smtp_user_policies'));
    }
}
