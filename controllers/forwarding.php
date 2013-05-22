<?php

/**
 * SMTP forwarding domain controller.
 *
 * @category   apps
 * @package    smtp
 * @subpackage controllers
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
 * SMTP forwarding domain controller.
 *
 * @category   apps
 * @package    smtp
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smtp/
 */

class Forwarding extends ClearOS_Controller
{
	/**
	 * Forward domains overview.
	 */

    function index()
	{
		// Load libraries
		//---------------

		$this->load->library('smtp/Postfix');
		$this->lang->load('smtp');

		// Load view data
		//---------------

		try {
            $data['mode'] = $mode;
			$data['forwarders'] = $this->postfix->get_forwarders();
		} catch (Exception $e) {
			$this->page->view_exception($e);
			return;
		}
 
		// Load views
		//-----------

        $this->page->view_form('smtp/forwarding/summary', $data, lang('smtp_mail_forwarding'));
	}

	/**
	 * Add domain.
	 */

	function add()
	{
		$this->_item('add');
	}

	/**
	 * Delete domain.
     *
     * @param string $domain domain 
     *
     * @return view
	 */

	function delete($domain)
	{
        $confirm_uri = '/app/smtp/forwarding/destroy/' . $domain;
        $cancel_uri = '/app/smtp/forwarding';
        $items = array($domain);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
	}

	/**
	 * Destroys domain.
     *
     * @param string $domain domain
     *
     * @return view
	 */

	function destroy($domain)
	{
		// Load libraries
		//---------------

		$this->load->library('smtp/Postfix');

		// Handle form submit
		//-------------------

		try {
			$this->postfix->delete_forwarder($domain);
            $this->postfix->reset();

			$this->page->set_status_deleted();
            redirect('/smtp/forwarding');
		} catch (Exception $e) {
			$this->page->view_exception($e);
			return;
		}
	}

	/**
	 * Edit domain.
     *
     * @param string $domain domain
     *
     * @return view
	 */

	function edit($domain)
	{
		$this->_item('edit', $domain);
	}

	///////////////////////////////////////////////////////////////////////////////
	// P R I V A T E
	///////////////////////////////////////////////////////////////////////////////

	/**
	 * Destination domain common add/edit form handler.
     *
     * @param string $form_type form type
     * @param string $domain    domain 
     *
     * @return view
	 */

	function _item($form_type, $domain = '')
	{
		// Load libraries
		//---------------

		$this->lang->load('smtp');
		$this->load->library('smtp/Postfix');

		// Set validation rules
		//---------------------

        $this->form_validation->set_policy('domain', 'smtp/Postfix', 'validate_forwarder_domain', TRUE);
        $this->form_validation->set_policy('server', 'smtp/Postfix', 'validate_server', TRUE);
        $this->form_validation->set_policy('port', 'smtp/Postfix', 'validate_port', TRUE);
		$form_ok = $this->form_validation->run();

		// Handle form submit
		//-------------------

		if ($this->input->post('submit') && ($form_ok === TRUE)) {
			try {
				$this->postfix->add_forwarder(
                    $this->input->post('domain'),
                    $this->input->post('server'),
                    $this->input->post('port')
                );

				$this->postfix->reset();

				$this->page->set_status_added();
				redirect('/smtp/forwarding');
			} catch (Exception $e) {
				$this->page->view_exception($e);
				return;
			}
		}

		// Load the view data 
		//------------------- 

		$data['form_type'] = $form_type;

        if (! $this->input->post('port'))
            $data['port'] = '25';
 
		// Load the views
		//---------------

        $this->page->view_form('smtp/forwarding/item', $data, lang('smtp_mail_forwarding'));
	}
}
