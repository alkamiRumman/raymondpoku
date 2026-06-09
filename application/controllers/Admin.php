<?php

/**
 * @property Admin_model $admin
 */
class Admin extends MY_Controller
{
	public $path = '/admin';

	function __construct()
	{
		parent::__construct();
		$this->ifNotLogin();
		$this->ifNotAdmin();
		$this->load->model('Admin_model', 'admin');
	}

	function index()
	{
		$this->data['title'] = 'Dashboard';
		$this->data['totalCaregiver'] = $this->admin->getTotalCaregiver();
		$this->data['totalClient'] = $this->admin->getTotalClient();
		$this->data['serviceHoursToday'] = $this->admin->getServiceHoursToday()->totalHours;
		$this->data['serviceHoursWeek'] = $this->admin->getServiceHoursWeek()->totalHours;
		$this->data['totalAmountToday'] = $this->admin->getTotalAmountToday()->totalAmount;
		$this->data['totalAmountWeek'] = $this->admin->getTotalAmountWeek()->totalAmount;
		$this->data['billedAmountToday'] = $this->admin->getBilledAmountToday()->billAmount;
		$this->data['billedAmountWeek'] = $this->admin->getBilledAmountWeek()->billAmount;
		$outstandingRow = $this->db->query("SELECT COALESCE(SUM(dueAmount),0) AS totalOutstanding FROM invoices WHERE status != 'Fully Paid'")->row();
		$this->data['totalOutstanding'] = $outstandingRow ? $outstandingRow->totalOutstanding : 0;
		$this->data['scheduledHours'] = json_encode($this->admin->getScheduledHourDashboard());
		$this->data['invoiceData'] = json_encode($this->admin->getInvoiceData());

		$query1 = $this->db->query("SELECT SUM(d.totalAmount) as totalAmount, SUM(d.paidAmount) as paidAmount, MONTHNAME(d.invoiceDate) as month_name FROM invoices d 
            join clients c on d.clientId = c.id where YEAR(d.invoiceDate) = '" . date('Y')
			. "' GROUP BY MONTH(d.invoiceDate) ORDER BY MONTH(d.invoiceDate) ASC")->result();
		foreach ($query1 as $row) {
//			return dnp($row);
			$data1['label'][] = $row->month_name;
			$data1['totalAmount'][] = $totalAmount = (isset($row->totalAmount) ? round($row->totalAmount, 2) : 0);
			$data1['paidAmount'][] = $paidAmount = isset($row->paidAmount) ? round($row->paidAmount, 2) : 0;
			$data1['totalDue'][] = round($totalAmount - $paidAmount, 2);
		}
		if ($data1) {
			$this->data['data1'] = json_encode($data1);
		}

		$query2 = $this->db->query("SELECT SUM(s.hours) as totalHours, MONTHNAME(s.date) as month_name FROM services s join caregivers c on s.caregiverId = c.id join clients cl on s.clientId = cl.id where YEAR(s.date) = '" . date("Y")
			. "' GROUP BY MONTH(s.date)")->result();

		$query3 = $this->db->query("SELECT SUM(i.totalHours) as totalInvoiceHours, MONTHNAME(i.invoiceDate) as month_name FROM invoices i join clients cl on i.clientId = cl.id where YEAR(i.invoiceDate) = '" . date('Y')
			. "' GROUP BY MONTH(i.invoiceDate)")->result();

		// return dnp($query3);
		$result = array();
		$data11 = array();


		foreach (array_merge($query2, $query3) as $entry) {
			if (!isset($result[$entry->month_name])) {
				$result[$entry->month_name] = $entry;
			} else {
				foreach ($entry as $key => $value) {
					$result[$entry->month_name]->$key = $value;
				}
			}
		}
		function getMonthNumber($month_name)
		{
			return date('n', strtotime($month_name . ' 1'));
		}

		$sorted_result = array_values($result);

		usort($sorted_result, function ($a, $b) {
			return getMonthNumber($a->month_name) - getMonthNumber($b->month_name);
		});

		$result = [];
		foreach ($sorted_result as $entry) {
			$result[$entry->month_name] = $entry;
		}

		foreach ($result as $row) {
//			return dnp($row);
			$data11['label'][] = $row->month_name;
			$data11['totalHours'][] = (isset($row->totalHours) ? round($row->totalHours, 2) : 0);
			$data11['totalInvoiceHours'][] = isset($row->totalInvoiceHours) ? round($row->totalInvoiceHours, 2) : 0;
		}

		if ($data11) {
			$this->data['data11'] = json_encode($data11);
		}
		$this->makeView('/index');
	}

	function caregivers()
	{
		$this->data['title'] = "Caregivers";
		$this->makeView('/caregivers');
	}

	// caregivers
	function addCaregiver()
	{
		$this->popupView('/addCaregiver');
	}

	function archivedCaregiver()
	{
		$this->popupView('/archivedCaregiver');
	}

	function saveCaregiver()
	{
		$arr['firstName'] = $this->input->post('firstName');
		$arr['lastName'] = $this->input->post('lastName');
		$arr['address'] = $this->input->post('address');
		$arr['phone'] = $this->input->post('phone');
		$arr['email'] = $this->input->post('email');
		$arr['sin'] = $this->input->post('sin');
		$arr['dateOfBirth'] = date('Y-m-d', strtotime($this->input->post('dateOfBirth')));
		$arr['hiringDate'] = date('Y-m-d', strtotime($this->input->post('hiringDate')));
		$arr['baseRate'] = $this->input->post('baseRate');
		$arr['position'] = $this->input->post('position');
		$arr['notes'] = $this->input->post('notes');
		$this->admin->saveCaregivers($arr);
		$this->session->set_flashdata('success', 'Caregiver Added Successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}


	function getCaregivers()
	{
		$action = '<div class="d-flex gap-1 justify-content-center">'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/editCaregiver/$1') . '\')" class="btn btn-xs btn-primary px-2 py-1" title="Edit"><i class="feather icon-edit-2"></i></a>'
			. '<a href="javascript:void(0);" onclick="showSwal(\'passing-parameter-execute-archive\', \'' . base_url('admin/changeStatus/$1') . '\')" class="btn btn-xs btn-outline-warning px-2 py-1" title="Archive"><i class="feather icon-archive"></i></a>'
			. '<button onclick="showSwal(\'passing-parameter-execute-delete\', \'' . base_url('admin/deleteCaregivers/$1') . '\')" class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></button>'
			. '</div>';
		$this->datatables->select('id, firstName, lastName, address, phone, email, sin, dateOfBirth, hiringDate, baseRate, position, notes, status, createAt, updateAt');
		$this->datatables->from(TABLE_CAREGIVERS);
		$this->datatables->where(array('status' => 0));
		$this->datatables->addColumn('actions', $action, 'id');
		$this->datatables->generate();
	}

	function getArchivedCaregivers()
	{
		$this->datatables->select('id, firstName, lastName, address, phone, email, sin, dateOfBirth, hiringDate, baseRate, position, notes, status, createAt, updateAt');
		$this->datatables->from(TABLE_CAREGIVERS);
		$this->datatables->where(array('status' => 1));
		$this->datatables->generate();
	}

	function editCaregiver($id)
	{
		$this->data['data'] = $this->admin->getCaregiversById($id);
		$this->popupView('/editCaregiver');
	}

	function updateCaregiver($id)
	{
//		return dnp($_POST);
		$arr['firstName'] = $this->input->post('firstName');
		$arr['lastName'] = $this->input->post('lastName');
		$arr['address'] = $this->input->post('address');
		$arr['phone'] = $this->input->post('phone');
		$arr['email'] = $this->input->post('email');
		$arr['sin'] = $this->input->post('sin');
		$arr['dateOfBirth'] = date('Y-m-d', strtotime($this->input->post('dateOfBirth')));
		$arr['hiringDate'] = date('Y-m-d', strtotime($this->input->post('hiringDate')));
		$arr['baseRate'] = $this->input->post('baseRate');
		$arr['position'] = $this->input->post('position');
		$arr['notes'] = $this->input->post('notes');
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateCaregivers($arr, $id);
		$this->session->set_flashdata('success', 'Caregiver Details Update Successfully.');
		redirect('admin/caregivers');
	}

	function changeStatus($id)
	{
		$arr['status'] = 1;
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateCaregivers($arr, $id);
		$this->session->set_flashdata('success', 'Caregiver Successfully Archived.');
		redirect('admin/caregivers');
	}

	function changeStatusActive($id)
	{
		$arr['status'] = 0;
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateCaregivers($arr, $id);
		$this->session->set_flashdata('success', 'Caregiver Successfully Activated.');
		redirect('admin/caregivers');
	}

	function deleteCaregivers($id)
	{
// 		$this->admin->deleteCaregivers($id);
// 		$this->session->set_flashdata('success', 'Caregiver Successfully Removed..');
        $this->session->set_flashdata('danger', 'Not Permitted!!');
		redirect('admin/caregivers');
	}

	// client
	function clients()
	{
		$this->data['title'] = "Clients";
		$this->makeView('/clients');
	}

	function addClient()
	{
		$this->popupView('/addClient');
	}

	function saveClient()
	{
		$arr['name'] = $this->input->post('name');
		$arr['address'] = $this->input->post('address');
		$arr['phone'] = $this->input->post('phone');
		$arr['dol'] = $this->input->post('dol');
		$arr['referralSource'] = $this->input->post('referralSource');
		$arr['budget'] = $this->input->post('budget');
		$arr['referralDate'] = date('Y-m-d', strtotime($this->input->post('referralDate')));
		$arr['billingAddress'] = $this->input->post('billingAddress');
		$arr['companyName'] = $this->input->post('companyName');
		$arr['adjustorName'] = $this->input->post('adjustorName');
		$arr['adjustorEmail'] = $this->input->post('adjustorEmail');
		$arr['adjustorPhone'] = $this->input->post('adjustorPhone');
		$arr['adjustorFax'] = $this->input->post('adjustorFax');
		$arr['billRate'] = $this->input->post('billRate');
		$arr['budgetedHours'] = $this->input->post('budgetedHours');
		$this->admin->saveClients($arr);
		$this->session->set_flashdata('success', 'Client Added Successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function getClients()
	{
		$action = '<div class="d-flex gap-1 justify-content-center">'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/editClient/$1') . '\')" class="btn btn-xs btn-primary px-2 py-1" title="Edit"><i class="feather icon-edit-2"></i></a>'
			. '<a href="' . base_url('admin/clientStatement/$1') . '" class="btn btn-xs btn-outline-info px-2 py-1" title="Statement"><i class="feather icon-file-text"></i></a>'
			. '<a href="javascript:void(0);" onclick="showSwal(\'passing-parameter-execute-archive\', \'' . base_url('admin/changeClientStatus/$1') . '\')" class="btn btn-xs btn-outline-warning px-2 py-1" title="Archive"><i class="feather icon-archive"></i></a>'
			. '<button onclick="showSwal(\'passing-parameter-execute-delete\', \'' . base_url('admin/deleteClients/$1') . '\')" class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></button>'
			. '</div>';
		$this->datatables->select('c.id as id, c.name, c.address, c.phone, c.dol, c.referralSource, c.budget, c.referralDate, c.billingAddress, c.companyName, c.adjustorName, c.adjustorEmail, c.adjustorPhone, c.adjustorFax, c.billRate, c.budgetedHours, c.status, COALESCE(SUM(i.total),0) as totalBilled, COALESCE(SUM(i.paidAmount),0) as totalPaid, COALESCE(SUM(i.dueAmount),0) as outstanding, c.createAt, c.updateAt');
		$this->datatables->from(TABLE_CLIENTS . ' as c');
		$this->datatables->join(TABLE_INVOICES . ' as i', 'i.clientId = c.id', 'left');
		$this->datatables->where(array('c.status' => 0));
		$this->datatables->group_by('c.id');
		$this->datatables->addColumn('actions', $action, 'id');
		$this->datatables->generate();
	}

	function archivedClient()
	{
		$this->popupView('/archivedClient');
	}

	function getArchivedClients()
	{

		$this->datatables->select('id, name, address, phone, dol, referralSource, budget, referralDate, billingAddress, companyName, adjustorName, adjustorEmail, 
		adjustorPhone, adjustorFax, billRate, budgetedHours, status, totalBilled, totalPaid, outstanding, createAt, updateAt');
		$this->datatables->from(TABLE_CLIENTS);
		$this->datatables->where(array('status' => 1));
		$this->datatables->generate();
	}

	function editClient($id)
	{
		$this->data['data'] = $this->admin->getClientsById($id);
		$this->popupView('/editClient');
	}

	function changeClientStatus($id)
	{
		$arr['status'] = 1;
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateClients($arr, $id);
		$this->session->set_flashdata('success', 'Client Successfully Archived.');
		redirect('admin/clients');
	}

	function changeClientStatusActive($id)
	{
		$arr['status'] = 0;
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateClients($arr, $id);
		$this->session->set_flashdata('success', 'Client Successfully Activated.');
		redirect('admin/clients');
	}

	function updateClient($id)
	{
//		return dnp($_POST);
		$arr['name'] = $this->input->post('name');
		$arr['address'] = $this->input->post('address');
		$arr['phone'] = $this->input->post('phone');
		$arr['dol'] = $this->input->post('dol');
		$arr['referralSource'] = $this->input->post('referralSource');
		$arr['budget'] = $this->input->post('budget');
		$arr['referralDate'] = date('Y-m-d', strtotime($this->input->post('referralDate')));
		$arr['billingAddress'] = $this->input->post('billingAddress');
		$arr['companyName'] = $this->input->post('companyName');
		$arr['adjustorName'] = $this->input->post('adjustorName');
		$arr['adjustorEmail'] = $this->input->post('adjustorEmail');
		$arr['adjustorPhone'] = $this->input->post('adjustorPhone');
		$arr['adjustorFax'] = $this->input->post('adjustorFax');
		$arr['billRate'] = $this->input->post('billRate');
		$arr['budgetedHours'] = $this->input->post('budgetedHours');
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateClients($arr, $id);
		$this->session->set_flashdata('success', 'Client Details Update Successfully.');
		redirect('admin/clients');
	}

	function deleteClients($id)
	{
// 		$this->admin->deleteClients($id);
// 		$this->session->set_flashdata('success', 'Client Successfully Removed..');
        $this->session->set_flashdata('danger', 'Not Permitted!!');
		redirect('admin/clients');
	}

	function services()
	{
		$this->data['title'] = "Services";
		$this->makeView('/services');
	}

	function addService()
	{
		$this->data['caregivers'] = $this->admin->getCaregivers();
		$this->data['clients'] = $this->admin->getClients();
		$this->popupView('/addService');
	}

	function addServiceFromCalendar($clientId, $date)
	{
		$this->data['client'] = $this->admin->getClientsById($clientId);
		$this->data['date'] = $date;
		$this->data['caregivers'] = $this->admin->getCaregivers();
		$this->data['clients'] = $this->admin->getClients();
		$this->popupView('/addServiceFromCalendar');
	}

	function getScheduledHoursThisMonth($clientId)
	{
		echo $this->admin->getScheduledHoursThisMonth($clientId) ? $this->admin->getScheduledHoursThisMonth($clientId)->totalHours : 0;
	}

	function getScheduledHoursOtherMonth($clientId, $month, $year)
	{
		echo $this->admin->getScheduledHoursOtherMonth($clientId, $month, $year) ? $this->admin->getScheduledHoursOtherMonth($clientId, $month, $year)->totalHours : 0;
	}

	function saveService()
	{
//		return dnp($_POST);
		$date_arr = explode(", ", $this->input->post('date'));
		for ($i = 0; $i < count($date_arr); $i++) {
			$arr['date'] = date('Y-m-d', strtotime($date_arr[$i]));
			$arr['startTime'] = $this->input->post('startTime');
			$arr['endTime'] = $this->input->post('endTime');
			$arr['caregiverId'] = $this->input->post('caregiverId');
			$clientId = $arr['clientId'] = $this->input->post('clientId');
			$arr['serviceType'] = $this->input->post('serviceType');
			$arr['status'] = $this->input->post('status') ?: 'scheduled';
			$arr['rate'] = $this->input->post('rate');
			$arr['billRate'] = $this->input->post('billRate');
			$arr['hours'] = $this->input->post('hours');
			$arr['amount'] = $this->input->post('amount');
			$arr['comments'] = $this->input->post('comments');
			$billAmount = $arr['billAmount'] = $this->input->post('billAmount');
			$this->admin->saveService($arr);
			$client = $this->admin->getClientsById($clientId);
			$billable = ($arr['status'] !== 'cancelled') ? $billAmount : 0;
			$cl['totalBilled'] = $client->totalBilled + $billable;
			$cl['outstanding'] = $cl['totalBilled'] - $client->totalPaid;
			$this->admin->updateClients($cl, $clientId);
		}
		$this->session->set_flashdata('success', 'Service Added Successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function saveServiceFromCalendar()
	{
		$arr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
		$arr['startTime'] = $this->input->post('startTime');
		$arr['endTime'] = $this->input->post('endTime');
		$arr['caregiverId'] = $this->input->post('caregiverId');
		$clientId = $arr['clientId'] = $this->input->post('clientId');
		$arr['serviceType'] = $this->input->post('serviceType');
		$arr['status'] = $this->input->post('status') ?: 'scheduled';
		$arr['rate'] = $this->input->post('rate');
		$arr['billRate'] = $this->input->post('billRate');
		$arr['hours'] = $this->input->post('hours');
		$arr['amount'] = $this->input->post('amount');
		$arr['comments'] = $this->input->post('comments');
		$billAmount = $arr['billAmount'] = $this->input->post('billAmount');
		$eventId = $this->admin->saveService($arr);
		$client = $this->admin->getClientsById($clientId);
		$billable = ($arr['status'] !== 'cancelled') ? $billAmount : 0;
		$cl['totalBilled'] = $client->totalBilled + $billable;
		$cl['outstanding'] = $cl['totalBilled'] - $client->totalPaid;
		$this->admin->updateClients($cl, $clientId);

		$eventData = array(
			'id' => $eventId,
			'date' => $arr['date'],
			'startTime' => $arr['startTime'],
			'endTime' => $arr['endTime'],
			'caregiverId' => $arr['caregiverId'],
			'clientId' => $arr['clientId'],
			'rate' => $arr['rate'],
			'hours' => $arr['hours'],
			'amount' => $arr['amount']
		);

		echo json_encode($eventData);
	}

	function editService($id)
	{
		$this->data['caregivers'] = $this->admin->getCaregivers();
		$this->data['clients'] = $this->admin->getClients();
		$this->data['data'] = $this->admin->getServiceById($id);
		$this->popupView('/editService');
	}

	function editServiceCalendar($id)
	{
		$this->data['caregivers'] = $this->admin->getCaregivers();
		$this->data['clients'] = $this->admin->getClients();
		$this->data['data'] = $this->admin->getServiceById($id);
		$this->popupView('/editServiceCalendar');
	}

	function getServices()
	{
		$action = '<div class="d-flex gap-1 justify-content-center">'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/editService/$1') . '\')" class="btn btn-xs btn-success px-2 py-1" title="Edit"><i class="feather icon-edit-2"></i></a>'
			. '<button onclick="showSwal(\'passing-parameter-execute-delete\', \'' . base_url('admin/deleteService/$1') . '\')" class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></button>'
			. '</div>';
		$this->datatables->select('s.id as id, c.firstName, c.lastName, cl.name, s.startTime, s.endTime, s.date, s.serviceType, s.status, s.rate, s.billRate, s.billAmount, s.hours, s.amount, s.comments, i.invoiceNumber, s.updateAt');
		$this->datatables->from(TABLE_SERVICES . ' as s');
		$this->datatables->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->datatables->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->datatables->join(TABLE_INVOICES . ' as i', 's.invoiceId = i.id', 'left');
		$this->datatables->where(array('c.status' => 0, 'cl.status' => 0));
		$this->datatables->addColumn('actions', $action, 'id');
		$this->datatables->generate();
	}

	function getServicesCustom($startDate, $endDate)
	{
		$action = '<div class="d-flex gap-1 justify-content-center">'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/editService/$1') . '\')" class="btn btn-xs btn-success px-2 py-1" title="Edit"><i class="feather icon-edit-2"></i></a>'
			. '<button onclick="showSwal(\'passing-parameter-execute-delete\', \'' . base_url('admin/deleteService/$1') . '\')" class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></button>'
			. '</div>';
		$this->datatables->select('s.id as id, c.firstName, c.lastName, cl.name, s.startTime, s.endTime, s.date, s.serviceType, s.status, s.rate, s.billRate, s.billAmount, s.hours, s.amount, s.comments, i.invoiceNumber, s.updateAt');
		$this->datatables->from(TABLE_SERVICES . ' as s');
		$this->datatables->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->datatables->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->datatables->join(TABLE_INVOICES . ' as i', 's.invoiceId = i.id', 'left');
		$this->datatables->where(array('s.date >=' => $startDate));
		$this->datatables->where(array('s.date <=' => $endDate));
		$this->datatables->where(array('c.status' => 0, 'cl.status' => 0));
		$this->datatables->addColumn('actions', $action, 'id');
		$this->datatables->generate();
	}

	function updateService($id)
	{
//		return dnp($_POST);
		if ($this->input->post('copyDate')) {
			$date_arr = explode(", ", $this->input->post('copyDate'));
			for ($i = 0; $i < count($date_arr); $i++) {
				$arr['date'] = date('Y-m-d', strtotime($date_arr[$i]));
				$arr['startTime'] = $this->input->post('startTime');
				$arr['endTime'] = $this->input->post('endTime');
				$arr['caregiverId'] = $this->input->post('caregiverId');
				$clientId = $arr['clientId'] = $this->input->post('clientId');
				$arr['serviceType'] = $this->input->post('serviceType');
				$arr['rate'] = $this->input->post('rate');
				$arr['billRate'] = $this->input->post('billRate');
				$arr['hours'] = $this->input->post('hours');
				$arr['amount'] = $this->input->post('amount');
				$arr['comments'] = $this->input->post('comments');
				$billAmount = $arr['billAmount'] = $this->input->post('billAmount');
				$existingService = $this->admin->getServiceByData(
					$clientId,
					$arr['caregiverId'],
					$arr['serviceType'],
					$arr['startTime'],
					$arr['endTime'],
					$arr['date']
				);
				if (!$existingService) {
					$this->admin->saveService($arr);
				}
				$client = $this->admin->getClientsById($clientId);
				$cl['totalBilled'] = $client->totalBilled + $billAmount;
				$cl['outstanding'] = $cl['totalBilled'] - $client->totalPaid;
				$this->admin->updateClients($cl, $clientId);
			}
		}
		$arrr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
		$arrr['startTime'] = $this->input->post('startTime');
		$arrr['endTime'] = $this->input->post('endTime');
		$arrr['caregiverId'] = $this->input->post('caregiverId');
		$clientId = $arrr['clientId'] = $this->input->post('clientId');
		$arrr['serviceType'] = $this->input->post('serviceType');
		$arrr['status'] = $this->input->post('status') ?: 'scheduled';
		$arrr['rate'] = $this->input->post('rate');
		$arrr['billRate'] = $this->input->post('billRate');
		$arrr['hours'] = $this->input->post('hours');
		$arrr['amount'] = $this->input->post('amount');
		$arrr['comments'] = $this->input->post('comments');
		$billAmount = $arrr['billAmount'] = $this->input->post('billAmount');
		$oldBillAmount = $this->input->post('oldBillAmount');
		$oldStatus = $this->input->post('oldStatus') ?: 'scheduled';
		$arrr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateService($arrr, $id);
		$client = $this->admin->getClientsById($clientId);
		$oldBillable = ($oldStatus !== 'cancelled') ? $oldBillAmount : 0;
		$newBillable = ($arrr['status'] !== 'cancelled') ? $billAmount : 0;
		$cl['totalBilled'] = $client->totalBilled + $newBillable - $oldBillable;
		$cl['outstanding'] = $cl['totalBilled'] - $client->totalPaid;
		$this->admin->updateClients($cl, $clientId);
		$this->session->set_flashdata('success', 'Service Update Successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function updateServiceCalendar($id)
	{
//		return dnp($_POST);
		$arr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
		$arr['startTime'] = $this->input->post('startTime');
		$arr['endTime'] = $this->input->post('endTime');
		$arr['caregiverId'] = $this->input->post('caregiverId');
		$clientId = $arr['clientId'] = $this->input->post('clientId');
		$oldClientId = $this->input->post('oldClientId');
		$arr['serviceType'] = $this->input->post('serviceType');
		$arr['status'] = $this->input->post('status') ?: 'scheduled';
		$arr['rate'] = $this->input->post('rate');
		$arr['billRate'] = $this->input->post('billRate');
		$arr['hours'] = $this->input->post('hours');
		$arr['amount'] = $this->input->post('amount');
		$arr['comments'] = $this->input->post('comments');
		$billAmount = $arr['billAmount'] = $this->input->post('billAmount');
		$oldBillAmount = $this->input->post('oldBillAmount');
		$oldStatus = $this->input->post('oldStatus') ?: 'scheduled';
		$arr['updateAt'] = date('Y-m-d H:i:s');
		$this->admin->updateService($arr, $id);
		$client = $this->admin->getClientsById($clientId);
		$oldBillable = ($oldStatus !== 'cancelled') ? $oldBillAmount : 0;
		$newBillable = ($arr['status'] !== 'cancelled') ? $billAmount : 0;
		$cl['totalBilled'] = $client->totalBilled + $newBillable - $oldBillable;
		$cl['outstanding'] = $cl['totalBilled'] - $client->totalPaid;
		$this->admin->updateClients($cl, $clientId);

		$eventData = array(
			'id' => $id,
			'date' => $arr['date'],
			'startTime' => $arr['startTime'],
			'endTime' => $arr['endTime'],
			'caregiverId' => $arr['caregiverId'],
			'clientId' => $arr['clientId'],
			'oldClientId' => $oldClientId,
			'rate' => $arr['rate'],
			'hours' => $arr['hours'],
			'amount' => $arr['amount']
		);
		echo json_encode($eventData);
	}

	public function copyServiceToNextMonth()
	{
		$clientId = $this->input->post('clientId');
		$month    = (int) $this->input->post('month');
		$year     = (int) $this->input->post('year');

		if ($month && $year) {
			$currentMonthStart = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
			$currentMonthEnd   = date('Y-m-t',  mktime(0, 0, 0, $month, 1, $year));
		} else {
			$currentMonthStart = date('Y-m-01');
			$currentMonthEnd   = date('Y-m-t');
		}

		$currentMonthData = $this->admin->getServicesByDateRange($currentMonthStart, $currentMonthEnd, $clientId);

//		return dnp($currentMonthData);
		if (!empty($currentMonthData)) {
			foreach ($currentMonthData as $data) {
				// Adjust the date to the next month
				$originalDate = strtotime($data->date);
				$newDate = strtotime('+1 month', $originalDate);

				// Check if the new date is valid
				if (date('m', $originalDate) == date('m', $newDate)) {
					// Invalid date (e.g., moving from 31st to a month with fewer days)
					$newDate = strtotime('last day of next month', $originalDate);
				}

				$formattedNewDate = date('Y-m-d', $newDate);

				$exists = $this->admin->checkServiceExists($data->caregiverId, $data->serviceType, $formattedNewDate, $data->startTime, $data->endTime);

				if (!$exists) {
					// Prepare new data for insertion
					$newData = [
						'date' => $formattedNewDate,
						'startTime' => $data->startTime,
						'endTime' => $data->endTime,
						'caregiverId' => $data->caregiverId,
						'clientId' => $data->clientId,
						'serviceType' => $data->serviceType,
						'rate' => $data->rate,
						'billRate' => $data->billRate,
						'hours' => $data->hours,
						'amount' => $data->amount,
						'billAmount' => $data->billAmount,
					];
					$this->admin->saveService($newData);
				}
			}
			$response = ['status' => 'success', 'message' => 'Data copied to next month successfully.'];
		} else {
			$response = ['status' => 'error', 'message' => 'No data to copy.'];
		}

		echo json_encode($response);
	}

	function deleteService($id)
	{
// 		$this->admin->deleteService($id);
// 		$this->session->set_flashdata('success', 'Service Successfully Removed..');
        $this->session->set_flashdata('danger', 'Not Permitted!!');
		redirect('admin/services');
	}

	function calendar()
	{
		$this->data['title'] = "Service Calendar";
		$this->data['clients'] = $this->admin->getClients();
		$this->makeView('/calendar');
	}

	function getDashboardScheduledHoursData($month, $year)
	{
		$month = (int) $month;
		$year  = (int) $year;
		$start = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
		$end   = date('Y-m-t', strtotime($start));
		$this->db->select('cl.name, SUM(s.amount) as totalAmount, SUM(s.hours) as totalHours');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->db->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->db->where('s.date >=', $start);
		$this->db->where('s.date <=', $end);
		$this->db->where(array('c.status' => 0, 'cl.status' => 0));
		$this->db->group_by('cl.id');
		echo json_encode($this->db->get()->result());
	}

	function getDashboardInvoiceBreakdownData($month, $year)
	{
		$month = (int) $month;
		$year  = (int) $year;
		$start = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
		$end   = date('Y-m-t', strtotime($start));
		$this->db->select('cl.name, SUM(i.total) as total, SUM(i.paidAmount) as paidAmount, SUM(i.dueAmount) as dueAmount');
		$this->db->from(TABLE_INVOICES . ' as i');
		$this->db->join(TABLE_CLIENTS . ' as cl', 'i.clientId = cl.id');
		$this->db->where('i.invoiceDate >=', $start);
		$this->db->where('i.invoiceDate <=', $end);
		$this->db->group_by('cl.id');
		echo json_encode($this->db->get()->result());
	}

	function getDashboardAnnualInvoiceData($year)
	{
		$year  = (int) $year;
		$query = $this->db->query("SELECT SUM(d.totalAmount) as totalAmount, SUM(d.paidAmount) as paidAmount, MONTHNAME(d.invoiceDate) as month_name
			FROM invoices d JOIN clients c ON d.clientId = c.id
			WHERE YEAR(d.invoiceDate) = $year
			GROUP BY MONTH(d.invoiceDate) ORDER BY MONTH(d.invoiceDate) ASC")->result();
		$data = array('label' => array(), 'totalAmount' => array(), 'paidAmount' => array(), 'totalDue' => array());
		foreach ($query as $row) {
			$data['label'][]       = $row->month_name;
			$total                 = isset($row->totalAmount) ? round($row->totalAmount, 2) : 0;
			$paid                  = isset($row->paidAmount)  ? round($row->paidAmount, 2)  : 0;
			$data['totalAmount'][] = $total;
			$data['paidAmount'][]  = $paid;
			$data['totalDue'][]    = round($total - $paid, 2);
		}
		echo json_encode($data);
	}

	function getDashboardAnnualHoursData($year)
	{
		$year    = (int) $year;
		$query2  = $this->db->query("SELECT SUM(s.hours) as totalHours, MONTHNAME(s.date) as month_name
			FROM services s JOIN caregivers c ON s.caregiverId = c.id JOIN clients cl ON s.clientId = cl.id
			WHERE YEAR(s.date) = $year GROUP BY MONTH(s.date)")->result();
		$query3  = $this->db->query("SELECT SUM(i.totalHours) as totalInvoiceHours, MONTHNAME(i.invoiceDate) as month_name
			FROM invoices i JOIN clients cl ON i.clientId = cl.id
			WHERE YEAR(i.invoiceDate) = $year GROUP BY MONTH(i.invoiceDate)")->result();
		$merged  = array();
		foreach (array_merge($query2, $query3) as $entry) {
			if (!isset($merged[$entry->month_name])) {
				$merged[$entry->month_name] = clone $entry;
			} else {
				foreach ($entry as $k => $v) {
					$merged[$entry->month_name]->$k = $v;
				}
			}
		}
		uksort($merged, function ($a, $b) {
			return date('n', strtotime($a . ' 1')) - date('n', strtotime($b . ' 1'));
		});
		$data = array('label' => array(), 'totalHours' => array(), 'totalInvoiceHours' => array());
		foreach ($merged as $row) {
			$data['label'][]             = $row->month_name;
			$data['totalHours'][]        = isset($row->totalHours)        ? round($row->totalHours, 2)        : 0;
			$data['totalInvoiceHours'][] = isset($row->totalInvoiceHours) ? round($row->totalInvoiceHours, 2) : 0;
		}
		echo json_encode($data);
	}

	function caregiverCalendar()
	{
		$this->data['title'] = "Caregiver Calendar";
		$this->data['caregivers'] = $this->admin->getCaregivers();
		$this->makeView('/caregiverCalendar');
	}

	function getCaregiverCalendarServices($caregiverId, $month, $year)
	{
		echo json_encode($this->admin->getServicesByCaregiverMonth($caregiverId, $month, $year));
	}

	function clientStatement($clientId)
	{
		$this->data['title'] = "Client Statement";
		$this->data['client'] = $this->admin->getClientsById($clientId);
		$this->data['invoices'] = $this->admin->getInvoicesByClientId($clientId);
		$this->makeView('/clientStatement');
	}

	function getAllService($clientId)
	{
		echo json_encode($this->admin->getAllService($clientId));
	}

	function caregiverWeekly()
	{
		$this->data['title'] = "Caregiver Weekly Total Hours";
		$this->data['caregivers'] = $this->admin->getCaregivers();
		$this->data['clients'] = $this->admin->getClients();
		$this->makeView('/caregiverWeekly');
	}

	function getCaregiverWeekly()
	{
		$startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
		$endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
		$this->data['data'] = $this->admin->getCaregiverWeekly($startDate, $endDate);
		echo json_encode($this->admin->getCaregiverWeekly($startDate, $endDate));
	}

	function invoice()
	{
		$this->data['title'] = "Monthly Invoice";
		$this->data['years'] = $this->admin->getYearsList();
		$this->makeView('/invoice');
	}

	function getInvoice()
	{
		$action = '<div class="d-flex gap-1 justify-content-center">'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/editInvoice/$1') . '\')" class="btn btn-xs btn-success px-2 py-1" title="Edit"><i class="feather icon-edit-2"></i></a>'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/printInvoice/$1') . '\')" class="btn btn-xs btn-info px-2 py-1" title="View"><i class="feather icon-eye"></i></a>'
			. '<a href="javascript:void(0);" onclick="confirmDeleteInvoice(\'' . base_url('admin/deleteInvoice/$1') . '\')" class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></a>'
			. '</div>';
		$this->datatables->select('i.id as id, i.invoiceDate, cl.name, cl.billingAddress, cl.phone, i.totalHours, i.total, i.paidAmount, i.dueAmount, i.status');
		$this->datatables->from(TABLE_INVOICES . ' as i');
		$this->datatables->join(TABLE_CLIENTS . ' as cl', 'i.clientId = cl.id');
		$this->datatables->addColumn('actions', $action, 'id');
		$this->datatables->generate();
	}

	function getCustomInvoice($month, $year)
	{
		$action = '<div class="d-flex gap-1 justify-content-center">'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/editInvoice/$1') . '\')" class="btn btn-xs btn-success px-2 py-1" title="Edit"><i class="feather icon-edit-2"></i></a>'
			. '<a href="javascript:void(0);" onclick="loadPopup(\'' . base_url('admin/printInvoice/$1') . '\')" class="btn btn-xs btn-info px-2 py-1" title="Print"><i class="feather icon-printer"></i></a>'
			. '<a href="javascript:void(0);" onclick="confirmDeleteInvoice(\'' . base_url('admin/deleteInvoice/$1') . '\')" class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></a>'
			. '</div>';
		$this->datatables->select('i.id as id, i.invoiceDate, cl.name, cl.billingAddress, cl.phone, i.totalHours, i.total, i.paidAmount, i.dueAmount, i.status');
		$this->datatables->from(TABLE_INVOICES . ' as i');
		$this->datatables->join(TABLE_CLIENTS . ' as cl', 'i.clientId = cl.id');
		if ($month != 'All') {
			$this->datatables->where(array('MONTH(i.invoiceDate)' => $month));
		}
		$this->datatables->where(array('YEAR(i.invoiceDate)' => $year));
		$this->datatables->addColumn('actions', $action, 'id');
		$this->datatables->generate();
	}

	function editInvoice($invoiceId)
	{
		$this->data['data']     = $this->admin->getInvoiceById($invoiceId);
		$this->data['services'] = $this->admin->getServiceByInvoiceId($invoiceId);
		$this->data['payments'] = $this->admin->getInvoicePayments($invoiceId);
		$this->popupView('/editInvoice');
	}

	function printInvoice($invoiceId)
	{
		$this->data['data']     = $this->admin->getInvoiceById($invoiceId);
		$this->data['services'] = $this->admin->getServiceByInvoiceId($invoiceId);
		$this->data['payments'] = $this->admin->getInvoicePayments($invoiceId);
		$this->popupView('/printInvoice');
	}

	function payInvoice($invoiceId)
	{
		$this->data['data']     = $this->admin->getInvoiceById($invoiceId);
		$this->data['payments'] = $this->admin->getInvoicePayments($invoiceId);
		$this->popupView('/payInvoice');
	}

	function savePay($id)
	{
		$arr['title']         = $this->input->post('title');
		$arr['poNumber']      = $this->input->post('poNumber');
		$arr['taxPercentage'] = $this->input->post('taxPercentage');
		$arr['taxAmount']     = $this->input->post('taxAmount');
		$arr['invoiceDate']   = date('Y-m-d', strtotime($this->input->post('invoiceDate')));
		$arr['dueDate']       = date('Y-m-d', strtotime($this->input->post('dueDate')));
		$clientId             = $this->input->post('clientId');
		$total                = $arr['total'] = $this->input->post('total');
		$paidAmount           = $this->input->post('paidAmount') ? $this->input->post('paidAmount') : 0;
		$invoice              = $this->admin->getInvoiceById($id);
		$arr['paidAmount']    = $invoice->paidAmount + $paidAmount;
		$arr['dueAmount']     = number_format(($total - $arr['paidAmount']), 2);
		if ($total == $arr['paidAmount']) {
			$arr['status'] = 'Fully Paid';
		}
		if ($total > $arr['paidAmount'] && $arr['paidAmount'] != 0) {
			$arr['status'] = 'Partial Paid';
		}
		$now = date('Y-m-d H:i:s');
		$payDateRaw = $this->input->post('payDate');
		$paidAt = $payDateRaw ? date('Y-m-d', strtotime($payDateRaw)) . ' ' . date('H:i:s') : $now;
		$ar['updateAt'] = $arr['updateAt'] = $now;
		$client              = $this->admin->getClientsById($clientId);
		$ar['totalPaid']     = $client->totalPaid + $paidAmount;
		$ar['outstanding']   = $client->totalBilled - $ar['totalPaid'];
		$this->admin->updateInvoice($arr, $id);
		$this->admin->updateClients($ar, $clientId);
		if ($paidAmount > 0) {
			$this->admin->saveInvoicePayment([
				'invoiceId' => $id,
				'amount'    => $paidAmount,
				'note'      => $this->input->post('payNote'),
				'paidAt'    => $paidAt,
			]);
		}
		$this->session->set_flashdata('success', 'Invoice updated successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function generateInvoice()
	{
		$this->data['title'] = 'Generate Invoice';
		$this->makeView('/generateInvoice');
	}

	function getServicesInvoice()
	{
		$this->datatables->select('s.id, i.invoiceNumber, c.firstName, c.lastName, cl.name, s.startTime, s.endTime, s.date, s.serviceType, s.status, s.rate, s.billRate, s.billAmount, s.hours, s.amount, s.updateAt');
		$this->datatables->from(TABLE_SERVICES . ' as s');
		$this->datatables->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->datatables->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->datatables->join(TABLE_INVOICES . ' as i', 's.invoiceId = i.id', 'left');
		$this->datatables->where(array('c.status' => 0, 'cl.status' => 0));
		$this->datatables->where("s.status != 'cancelled'");
		$this->datatables->generate();
	}

	function getServicesCustomInvoice($startDate, $endDate)
	{
		$this->datatables->select('s.id as id, i.invoiceNumber, c.firstName, c.lastName, cl.name, s.startTime, s.endTime, s.date, s.serviceType, s.status, s.rate, s.billRate, s.billAmount, s.hours, s.amount, s.updateAt');
		$this->datatables->from(TABLE_SERVICES . ' as s');
		$this->datatables->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->datatables->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->datatables->join(TABLE_INVOICES . ' as i', 's.invoiceId = i.id', 'left');
		$this->datatables->where(array('s.date >=' => $startDate));
		$this->datatables->where(array('s.date <=' => $endDate));
		$this->datatables->where(array('c.status' => 0, 'cl.status' => 0));
		$this->datatables->where("s.status != 'cancelled'");
		$this->datatables->generate();
	}

	function generate_invoice()
	{
		$this->data['selectedRows'] = $this->input->post('selectedRows');
		$service = $this->admin->getServiceById($this->input->post('selectedRows')[0]['id']);
		$totalAmount = 0;
		$totalHours = 0;
		for ($i = 0; $i < count($this->input->post('selectedRows')); $i++) {
			$totalAmount += $this->input->post('selectedRows')[$i]['billAmount'];
			$totalHours += $this->input->post('selectedRows')[$i]['hours'];
		}
		$this->data['totalAmount'] = $totalAmount;
		$this->data['totalHours'] = $totalHours;
		$this->data['client'] = $this->admin->getClientsById($service->clientId);
		$this->load->view('admin/generate_invoice', $this->data);
	}

	function saveGenerateInvoice()
	{
//		return dnp($_POST);
		$arr['title'] = $this->input->post('title');
		$clientId = $arr['clientId'] = $this->input->post('clientId');
		$arr['poNumber'] = $this->input->post('poNumber');
		$invouceNumber = $arr['invoiceNumber'] = $this->input->post('invoiceNumber');
		$arr['invoiceDate'] = date('Y-m-d', strtotime($this->input->post('invoiceDate')));
		$arr['dueDate'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
		$arr['totalAmount'] = $this->input->post('totalAmount');
		$arr['totalHours'] = $this->input->post('totalHours');
		$arr['taxPercentage'] = $this->input->post('taxPercentage');
		$arr['taxAmount'] = $this->input->post('taxAmount');
		$arr['total'] = $this->input->post('total');
		$paidAmount = $arr['paidAmount'] = $this->input->post('paidAmount') ? $this->input->post('paidAmount') : 0;
		$arr['dueAmount'] = $arr['total'] - $paidAmount;
		if ($arr['total'] == $arr['paidAmount']) {
			$arr['status'] = 'Fully Paid';
		} elseif ($arr['paidAmount'] > 0 && $arr['total'] > $arr['paidAmount']) {
			$arr['status'] = 'Partial Paid';
		} else {
			$arr['status'] = 'Sent';
		}
		$this->admin->saveInvoice($arr);
		$invouceId = $this->db->insert_id();
		for ($i = 0; $i < count($this->input->post('serviceId')); $i++) {
			$ar['invoiceId'] = $invouceId;
			$this->admin->updateService($ar, $this->input->post('serviceId')[$i]);
		}
		$client = $this->admin->getClientsById($clientId);
		$arrr['totalPaid'] = $client->totalPaid + $paidAmount;
		$arrr['outstanding'] = $client->totalBilled - $arrr['totalPaid'];
		$this->admin->updateClients($arrr, $client->id);
		$this->session->set_flashdata('success', 'Invoice Generated Successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function deleteInvoice($id)
	{
		$this->admin->deleteInvoiceById($id);
		$this->session->set_flashdata('success', 'Invoice deleted successfully.');
		redirect(admin_url('invoice'));
	}

	// ---------------------------------------------------------------
	// Payments module
	// ---------------------------------------------------------------

	function payments()
	{
		$this->data['title'] = 'Payments';
		$this->makeView('/payments');
	}

	function getPaymentsData()
	{
		$payments = $this->admin->getPayments();
		$result   = [];
		foreach ($payments as $p) {
			$items = $this->admin->getPaymentItems($p->id);
			$invoiceList = implode(', ', array_map(function ($i) {
				return $i->invoiceNumber . ' ($' . number_format($i->amountApplied, 2) . ')';
			}, $items));
			$result[] = [
				'id'          => $p->id,
				'paymentDate' => $p->paymentDate,
				'clientName'  => $p->clientName,
				'totalAmount' => $p->totalAmount,
				'taxAmount'   => $p->taxAmount,
				'invoiceList' => $invoiceList ?: '—',
				'note'        => $p->note,
			];
		}
		echo json_encode($result);
	}

	function addPayment()
	{
		$this->data['clients'] = $this->admin->getClients();
		$this->popupView('/addPayment');
	}

	function getClientInvoices($clientId)
	{
		$invoices = $this->admin->getUnpaidInvoicesByClient($clientId);
		echo json_encode($invoices);
	}

	function savePayment()
	{
		$clientId      = $this->input->post('clientId');
		$paymentDate   = date('Y-m-d', strtotime($this->input->post('paymentDate')));
		$note          = $this->input->post('note');
		$taxPercentage = $this->input->post('taxPercentage') ?: 0;
		$taxAmount     = $this->input->post('taxAmount')     ?: 0;
		$invoiceIds    = $this->input->post('invoiceIds');   // array [invoiceId => amount]

		if (empty($invoiceIds)) {
			$this->session->set_flashdata('error', 'No invoices selected.');
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		// Compute total from the submitted invoice amounts
		$totalAmount = array_sum($invoiceIds);

		$paymentId = $this->admin->savePayment([
			'clientId'      => $clientId,
			'paymentDate'   => $paymentDate,
			'totalAmount'   => $totalAmount,
			'taxAmount'     => $taxAmount,
			'note'          => $note,
			'createdAt'     => date('Y-m-d H:i:s'),
		]);

		$now = date('Y-m-d H:i:s');
		foreach ($invoiceIds as $invoiceId => $amountApplied) {
			$amountApplied = (float) $amountApplied;
			if ($amountApplied <= 0) continue;

			$this->admin->savePaymentItem([
				'paymentId'     => $paymentId,
				'invoiceId'     => $invoiceId,
				'amountApplied' => $amountApplied,
			]);

			// Update invoice paid/due/status
			$invoice     = $this->admin->getInvoiceById($invoiceId);
			$newPaid     = $invoice->paidAmount + $amountApplied;
			$newDue      = $invoice->total - $newPaid;
			if ($newDue <= 0) {
				$status = 'Fully Paid';
			} elseif ($newPaid > 0) {
				$status = 'Partial Paid';
			} else {
				$status = $invoice->status;
			}
			$this->admin->updateInvoice([
				'paidAmount' => $newPaid,
				'dueAmount'  => $newDue,
				'status'     => $status,
				'updateAt'   => $now,
			], $invoiceId);

			// Write to invoice_payments for backward-compatible payment history on editInvoice
			$this->admin->saveInvoicePayment([
				'invoiceId' => $invoiceId,
				'amount'    => $amountApplied,
				'note'      => $note,
				'paidAt'    => $now,
			]);
		}

		// Update client totals
		$client  = $this->admin->getClientsById($clientId);
		$newPaid = $client->totalPaid + $totalAmount;
		$this->admin->updateClients([
			'totalPaid'   => $newPaid,
			'outstanding' => $client->totalBilled - $newPaid,
		], $clientId);

		$this->session->set_flashdata('success', 'Payment recorded successfully.');
		redirect(admin_url('payments'));
	}

	function deletePayment($id)
	{
		$this->admin->deletePaymentById($id);
		$this->session->set_flashdata('success', 'Payment deleted and invoice balances reversed.');
		redirect(admin_url('payments'));
	}

	function viewPayment($id)
	{
		$this->data['payment'] = $this->admin->getPaymentById($id);
		$this->data['items']   = $this->admin->getPaymentItems($id);
		$this->popupView('/viewPayment');
	}

	function editPayment($id)
	{
		$this->data['payment'] = $this->admin->getPaymentById($id);
		$this->data['items']   = $this->admin->getPaymentItems($id);
		$this->popupView('/editPayment');
	}

	function updatePayment($id)
	{
		$paymentDate = date('Y-m-d', strtotime($this->input->post('paymentDate')));
		$note        = $this->input->post('note');
		$this->admin->updatePayment(['paymentDate' => $paymentDate, 'note' => $note], $id);
		$this->session->set_flashdata('success', 'Payment updated successfully.');
		redirect(admin_url('payments'));
	}

}
