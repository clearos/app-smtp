<?php

/**
 * SMTP general settings controller.
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
 * SMTP general settings controller.
 *
 * @category   apps
 * @package    smtp
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smtp/
 */

class Settings extends ClearOS_Controller
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
         
        $this->form_validation->set_policy('domain', 'smtp/Postfix', 'validate_domain', TRUE);
        $this->form_validation->set_policy('hostname', 'smtp/Postfix', 'validate_hostname', TRUE);
        $this->form_validation->set_policy('relay_host', 'smtp/Postfix', 'validate_relay_host');
        $this->form_validation->set_policy('max_message_size', 'smtp/Postfix', 'validate_max_message_size', TRUE);

        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if (($this->input->post('submit') && $form_ok)) {
            try {
                $this->postfix->set_hostname($this->input->post('hostname'));
                $this->postfix->set_domain($this->input->post('domain'));
                $this->postfix->set_relay_host($this->input->post('relay_host'));
                $this->postfix->set_max_message_size($this->input->post('max_message_size'));

                $this->postfix->reset();

                $this->page->set_status_updated();
                redirect('/smtp/settings');
            } catch (Engine_Exception $e) {
                $this->page->view_exception($e->get_message());
                return;
            }
        }

        // Load view data
        //---------------

        try {
            $data['form_type'] = $form_type;
            $data['domain'] = $this->postfix->get_domain();
            $data['hostname'] = $this->postfix->get_hostname();
            $data['max_message_size'] = $this->postfix->get_max_message_size();
            $data['relay_host'] = $this->postfix->get_relay_host();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('smtp/settings', $data);
    }
}
