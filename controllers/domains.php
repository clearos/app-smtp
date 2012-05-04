<?php

/**
 * SMTP destination domain controller.
 *
 * @category   Apps
 * @package    SMTP
 * @subpackage Controllers
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
 * SMTP destination domain controller.
 *
 * @category   Apps
 * @package    SMTP
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/smtp/
 */

class Domains extends ClearOS_Controller
{
	/**
	 * Destination domains overview.
	 */

    function index($mode = 'edit')
	{
		// Load libraries
		//---------------

		$this->load->library('smtp/Postfix');
		$this->lang->load('smtp');

		// Load view data
		//---------------

		try {
            $data['mode'] = $mode;
			$data['domains'] = $this->postfix->get_destinations();
		} catch (Exception $e) {
			$this->page->view_exception($e);
			return;
		}
 
		// Load views
		//-----------

        $this->page->view_form('smtp/domains/summary', $data, lang('smtp_destination_domains'));
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
        $confirm_uri = '/app/smtp/domains/destroy/' . $domain;
        $cancel_uri = '/app/smtp/domains';
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
			$this->postfix->delete_destination($domain);
            $this->postfix->reset();

			$this->page->set_status_deleted();
            redirect('/smtp/domains');
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

        $this->form_validation->set_policy('domain', 'smtp/Postfix', 'validate_destination_domain', TRUE);
		$form_ok = $this->form_validation->run();

		// Handle form submit
		//-------------------

		if ($this->input->post('submit') && ($form_ok === TRUE)) {
			try {
				$this->postfix->add_destination($this->input->post('domain'));
				$this->postfix->reset();

				$this->page->set_status_added();
				redirect('/smtp/domains');
			} catch (Exception $e) {
				$this->page->view_exception($e);
				return;
			}
		}

		// Load the view data 
		//------------------- 

		$data['form_type'] = $form_type;
 
		// Load the views
		//---------------

        $this->page->view_form('smtp/domains/item', $data, lang('smtp_destination_domains'));
	}
}
