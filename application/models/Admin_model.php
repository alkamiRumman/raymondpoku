<?php

class Admin_model extends CI_Model
{

	function getScheduledHourDashboard()
	{
		$start = date('Y-m-01');
		$end = date('Y-m-t');

		$this->db->select('s.*, c.firstName, c.lastName, cl.name, SUM(amount) as totalAmount, SUM(hours) as totalHours');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->db->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->db->where('s.date >=', $start);
		$this->db->where('s.date <=', $end);
		$this->db->where(array('c.status' => 0, 'cl.status' => 0));
		$this->db->group_by('s.caregiverId, s.clientId');
		return $this->db->get()->result();
	}

	function getTotalAmountTotalPayDashboard()
	{
		$start = date('Y-m-01');
		$end = date('Y-m-t');
//		return $end;
		$this->db->select('SUM(s.billAmount) as totalBillAmount, SUM(v.paidAmount) as paidAmount, MONTH(v.invoiceDate) as monthName');
		$this->db->from(TABLE_SERVICES . ' as s');
//		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
//		$this->db->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->db->join(TABLE_INVOICES . ' as v', 's.invoiceId = v.id','left');
		$this->db->where('s.date >=', $start);
		$this->db->where('s.date <=', $end);
		$this->db->or_where('v.invoiceDate >=', $start);
		$this->db->or_where('v.invoiceDate <=', $end);
//		$this->db->where(array('c.status' => 0, 'cl.status' => 0));
		$this->db->group_by('MONTH(s.date), MONTH(v.invoiceDate)');
		return $this->db->get()->result();
	}

	function getInvoiceData()
	{
		$start = date('Y-m-01');
		$end = date('Y-m-t');

		$this->db->select('cl.name, SUM(i.total) as total, SUM(i.paidAmount) as paidAmount, SUM(i.dueAmount) as dueAmount');
		$this->db->from(TABLE_INVOICES . ' as i');
		$this->db->join(TABLE_CLIENTS . ' as cl', 'i.clientId = cl.id');
		$this->db->where('i.invoiceDate >=', $start);
		$this->db->where('i.invoiceDate <=', $end);
		$this->db->group_by('cl.id');
		return $this->db->get()->result();
	}

	function getTotalCaregiver()
	{
		$this->db->select('*');
		$this->db->from(TABLE_CAREGIVERS);
		$this->db->where('status', 0);
		return $this->db->get()->num_rows();
	}

	function getTotalClient()
	{
		$this->db->select('*');
		$this->db->from(TABLE_CLIENTS);
		$this->db->where('status', 0);
		return $this->db->get()->num_rows();
	}

	function getServiceHoursToday()
	{
		$this->db->select('SUM(hours) as totalHours');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('date', date('Y-m-d'));
		return $this->db->get()->row();
	}

	function getServiceHoursWeek()
	{
		$currentDate = date('Y-m-d');

		$startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));
		$endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($currentDate)));

		$this->db->select('SUM(hours) as totalHours');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('date >=', $startOfWeek);
		$this->db->where('date <=', $endOfWeek);
		return $this->db->get()->row();
	}

	function getTotalAmountToday()
	{
		$this->db->select('SUM(amount) as totalAmount');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('date', date('Y-m-d'));
		return $this->db->get()->row();
	}

	function getTotalAmountWeek()
	{
		$currentDate = date('Y-m-d');

		$startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));
		$endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($currentDate)));

		$this->db->select('SUM(amount) as totalAmount');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('date >=', $startOfWeek);
		$this->db->where('date <=', $endOfWeek);
		return $this->db->get()->row();
	}

	function getBilledAmountToday()
	{
		$this->db->select('SUM(billAmount) as billAmount');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('date', date('Y-m-d'));
		return $this->db->get()->row();
	}

	function getBilledAmountWeek()
	{
		$currentDate = date('Y-m-d');

		$startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));
		$endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($currentDate)));

		$this->db->select('SUM(billAmount) as billAmount');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('date >=', $startOfWeek);
		$this->db->where('date <=', $endOfWeek);
		return $this->db->get()->row();
	}

	function saveCaregivers($arr)
	{
		$this->db->insert(TABLE_CAREGIVERS, $arr);
	}

	function updateCaregivers($arr, $id)
	{
		$this->db->update(TABLE_CAREGIVERS, $arr, array('id' => $id));
	}

	function deleteCaregivers($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(TABLE_CAREGIVERS);
	}

	function getCaregiversById($id)
	{
		$this->db->select('*');
		$this->db->from(TABLE_CAREGIVERS);
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	function getCaregivers()
	{
		$this->db->select('*');
		$this->db->from(TABLE_CAREGIVERS);
		$this->db->where('status', 0);
		return $this->db->get()->result();
	}

	function saveClients($arr)
	{
		$this->db->insert(TABLE_CLIENTS, $arr);
	}

	function updateClients($arr, $id)
	{
		$this->db->update(TABLE_CLIENTS, $arr, array('id' => $id));
	}

	function deleteClients($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(TABLE_CLIENTS);
	}

	function getClientsById($id)
	{
		$this->db->select('*');
		$this->db->from(TABLE_CLIENTS);
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	function getClients()
	{
		$this->db->select('*');
		$this->db->from(TABLE_CLIENTS);
		$this->db->where('status', 0);
		return $this->db->get()->result();
	}

	function saveUser($arr)
	{
		$this->db->insert(TABLE_USERS, $arr);
	}

	function updateUser($arr, $id)
	{
		$this->db->update(TABLE_USERS, $arr, array('id' => $id));
	}

//	function deleteUser($id)
//	{
//		$this->db->where('id', $id);
//		$this->db->delete(TABLE_USERS);
//	}

	function savePackageHistory($arr)
	{
		$this->db->insert(TABLE_PACKAGEHISTORY, $arr);
	}

	function getUserById($id)
	{
		$this->db->select('*');
		$this->db->from(TABLE_USERS);
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	function getPackageHistoryByUserId($id)
	{
		$this->db->select('h.*, u.name');
		$this->db->from(TABLE_PACKAGEHISTORY . ' as h');
		$this->db->join(TABLE_USERS . ' as u', 'h.addedBy = u.id');
		$this->db->where('h.userId', $id);
		$this->db->order_by('h.id', 'desc');
		return $this->db->get()->result();
	}

	function getServiceById($id)
	{
		$this->db->select('*');
		$this->db->from(TABLE_SERVICES);
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	function saveService($arr)
	{
		$this->db->insert(TABLE_SERVICES, $arr);
	}

	function updateService($arr, $id)
	{
		$this->db->update(TABLE_SERVICES, $arr, array('id' => $id));
	}

	function deleteService($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(TABLE_SERVICES);
	}

	function getAllService($clientId)
	{
		$this->db->select('s.id, s.date, s.hours, s.startTime, s.endTime, s.serviceType, s.status, c.firstName, c.lastName');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->db->where('s.clientId', $clientId);
		return $this->db->get()->result();
	}

	public function getServiceByData($clientId, $caregiverId, $serviceType, $startTime, $endTime, $date)
	{
		$this->db->where('clientId', $clientId);
		$this->db->where('caregiverId', $caregiverId);
		$this->db->where('serviceType', $serviceType);
		$this->db->where('startTime', $startTime);
		$this->db->where('endTime', $endTime);
		$this->db->where('date', $date);
		$query = $this->db->get(TABLE_SERVICES);
		return $query->row();
	}

	public function checkServiceExists($caregiverId, $serviceType, $date, $startTime, $endTime)
	{
		$this->db->where('caregiverId', $caregiverId);
		$this->db->where('serviceType', $serviceType);
		$this->db->where('date', $date);
		$this->db->where('startTime', $startTime);
		$this->db->where('endTime', $endTime);
		$query = $this->db->get(TABLE_SERVICES);
		return $query->num_rows() > 0;
	}

	public function getServicesByDateRange($startDate, $endDate, $clientId)
	{
		$this->db->where('date >=', $startDate);
		$this->db->where('date <=', $endDate);
		$this->db->where('clientId', $clientId);
		$query = $this->db->get(TABLE_SERVICES);
		return $query->result();
	}

	function getScheduledHoursThisMonth($clientId)
	{
		$this->db->select('SUM(s.hours) as totalHours');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CLIENTS . ' as c', 's.clientId = c.id');
		$this->db->where('s.clientId', $clientId);
		$this->db->where('MONTH(s.date)', date('m'));
		$this->db->group_by('c.id');
		return $this->db->get()->row();
	}

	function getScheduledHoursOtherMonth($clientId, $month, $year)
	{
		$this->db->select('SUM(s.hours) as totalHours');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CLIENTS . ' as c', 's.clientId = c.id');
		$this->db->where('s.clientId', $clientId);
		$this->db->where('MONTH(s.date)', $month);
		$this->db->where('YEAR(s.date)', $year);
		$this->db->group_by('c.id');
		return $this->db->get()->row();
	}

	function getCaregiverWeekly($start, $end)
	{
		$sql = "SELECT c.firstName, c.lastName, cl.name, s.caregiverId, s.clientId,
			SUM(CASE WHEN s.status IN ('scheduled','complete','late_cancellation') THEN s.hours ELSE 0 END) AS payableHours,
			SUM(CASE WHEN s.status = 'late_cancellation' THEN s.hours ELSE 0 END) AS lateCancelHours,
			SUM(CASE WHEN s.status = 'cancelled' THEN s.hours ELSE 0 END) AS cancelledHours,
			SUM(CASE WHEN s.status IN ('scheduled','complete','late_cancellation') THEN s.amount ELSE 0 END) AS totalAmount
			FROM " . TABLE_SERVICES . " s
			JOIN " . TABLE_CAREGIVERS . " c ON s.caregiverId = c.id
			JOIN " . TABLE_CLIENTS . " cl ON s.clientId = cl.id
			WHERE s.date >= ? AND s.date <= ? AND c.status = 0 AND cl.status = 0
			GROUP BY s.caregiverId, s.clientId";
		return $this->db->query($sql, [$start, $end])->result();
	}

	function getYearsList()
	{
		$this->db->select('YEAR(date) as year');
		$this->db->from(TABLE_SERVICES);
		$this->db->group_by('year');
		$this->db->order_by('YEAR(date)', 'asc');
		return $this->db->get()->result();
	}

	function getServiceMonthly($clientId, $month, $year)
	{
		$this->db->select('s.*, c.firstName, c.lastName, cl.name, amount, hours');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->db->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->db->where('s.clientId', $clientId);
		$this->db->where('MONTH(s.date)', $month);
		$this->db->where('YEAR(s.date)', $year);
		$this->db->order_by('s.date', 'DESC');
		return $this->db->get()->result();
	}

	function saveInvoice($arr)
	{
		$this->db->insert(TABLE_INVOICES, $arr);
	}

	function updateInvoice($arr, $id)
	{
		$this->db->update(TABLE_INVOICES, $arr, array('id' => $id));
	}

	function getInvoiceById($id)
	{
		$this->db->select('i.*, cl.name, cl.billingAddress, cl.companyName, cl.phone');
		$this->db->from(TABLE_INVOICES . ' as i');
		$this->db->join(TABLE_CLIENTS . ' as cl', 'i.clientId = cl.id');
		$this->db->where('i.id', $id);
		return $this->db->get()->row();
	}

	function getServiceByInvoiceId($id)
	{
		$this->db->select('s.*, c.firstName, c.lastName, cl.name, cl.billingAddress, cl.companyName, cl.phone');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->db->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->db->where('s.invoiceId', $id);
		return $this->db->get()->result();
	}

	function getServicesByCaregiverMonth($caregiverId, $month, $year)
	{
		$this->db->select('s.*, c.firstName, c.lastName, cl.name as clientName');
		$this->db->from(TABLE_SERVICES . ' as s');
		$this->db->join(TABLE_CAREGIVERS . ' as c', 's.caregiverId = c.id');
		$this->db->join(TABLE_CLIENTS . ' as cl', 's.clientId = cl.id');
		$this->db->where('s.caregiverId', $caregiverId);
		$this->db->where('MONTH(s.date)', $month);
		$this->db->where('YEAR(s.date)', $year);
		$this->db->order_by('s.date', 'ASC');
		$this->db->order_by('s.startTime', 'ASC');
		return $this->db->get()->result();
	}

	function getInvoicesByClientId($clientId)
	{
		$this->db->select('*');
		$this->db->from(TABLE_INVOICES);
		$this->db->where('clientId', $clientId);
		$this->db->order_by('invoiceDate', 'ASC');
		return $this->db->get()->result();
	}

	function saveInvoicePayment($arr)
	{
		$this->db->insert(TABLE_INVOICE_PAYMENTS, $arr);
	}

	function getInvoicePayments($invoiceId)
	{
		$this->db->select('*');
		$this->db->from(TABLE_INVOICE_PAYMENTS);
		$this->db->where('invoiceId', $invoiceId);
		$this->db->order_by('paidAt', 'ASC');
		return $this->db->get()->result();
	}

	function deleteInvoiceById($id)
	{
		$invoice = $this->getInvoiceById($id);
		if (!$invoice) return;

		// Reverse client paid amount
		$client = $this->getClientsById($invoice->clientId);
		$newPaid = max(0, $client->totalPaid - $invoice->paidAmount);
		$this->db->update(TABLE_CLIENTS, [
			'totalPaid'   => $newPaid,
			'outstanding' => $client->totalBilled - $newPaid,
		], ['id' => $invoice->clientId]);

		// Unlink services so they can be re-invoiced
		$this->db->update(TABLE_SERVICES, ['invoiceId' => null], ['invoiceId' => $id]);

		// Delete payment records for this invoice
		$this->db->delete(TABLE_INVOICE_PAYMENTS, ['invoiceId' => $id]);

		// Delete payment_items referencing this invoice
		$this->db->delete(TABLE_PAYMENT_ITEMS, ['invoiceId' => $id]);

		$this->db->delete(TABLE_INVOICES, ['id' => $id]);
	}

	// --- Payments module ---

	function savePayment($arr)
	{
		$this->db->insert(TABLE_PAYMENTS, $arr);
		return $this->db->insert_id();
	}

	function updatePayment($arr, $id)
	{
		$this->db->update(TABLE_PAYMENTS, $arr, ['id' => $id]);
	}

	function savePaymentItem($arr)
	{
		$this->db->insert(TABLE_PAYMENT_ITEMS, $arr);
	}

	function getPayments()
	{
		$this->db->select('p.*, cl.name as clientName');
		$this->db->from(TABLE_PAYMENTS . ' as p');
		$this->db->join(TABLE_CLIENTS . ' as cl', 'p.clientId = cl.id');
		$this->db->order_by('p.paymentDate', 'DESC');
		return $this->db->get()->result();
	}

	function getPaymentById($id)
	{
		$this->db->select('p.*, cl.name as clientName');
		$this->db->from(TABLE_PAYMENTS . ' as p');
		$this->db->join(TABLE_CLIENTS . ' as cl', 'p.clientId = cl.id');
		$this->db->where('p.id', $id);
		return $this->db->get()->row();
	}

	function getPaymentItems($paymentId)
	{
		$this->db->select('pi.*, i.invoiceNumber, i.total as invoiceTotal, i.paidAmount as invoicePaid');
		$this->db->from(TABLE_PAYMENT_ITEMS . ' as pi');
		$this->db->join(TABLE_INVOICES . ' as i', 'pi.invoiceId = i.id');
		$this->db->where('pi.paymentId', $paymentId);
		return $this->db->get()->result();
	}

	function getUnpaidInvoicesByClient($clientId)
	{
		$this->db->select('id, invoiceNumber, invoiceDate, total, paidAmount, dueAmount, status');
		$this->db->from(TABLE_INVOICES);
		$this->db->where('clientId', $clientId);
		$this->db->where('total > paidAmount');
		$this->db->order_by('invoiceDate', 'ASC');
		return $this->db->get()->result();
	}

	function deletePaymentById($id)
	{
		$payment  = $this->getPaymentById($id);
		if (!$payment) return;

		$items = $this->getPaymentItems($id);
		foreach ($items as $item) {
			$inv = $this->getInvoiceById($item->invoiceId);
			if (!$inv) continue;

			$newPaid = max(0, $inv->paidAmount - $item->amountApplied);
			$newDue  = $inv->total - $newPaid;
			$status  = $newPaid <= 0 ? 'Sent' : ($newDue > 0 ? 'Partial Paid' : 'Fully Paid');
			$this->db->update(TABLE_INVOICES, [
				'paidAmount' => $newPaid,
				'dueAmount'  => $newDue,
				'status'     => $status,
			], ['id' => $item->invoiceId]);

			$this->db->delete(TABLE_INVOICE_PAYMENTS, ['invoiceId' => $item->invoiceId, 'amount' => $item->amountApplied]);
		}

		// Reverse client totals
		$client  = $this->getClientsById($payment->clientId);
		$newPaid = max(0, $client->totalPaid - $payment->totalAmount);
		$this->db->update(TABLE_CLIENTS, [
			'totalPaid'   => $newPaid,
			'outstanding' => $client->totalBilled - $newPaid,
		], ['id' => $payment->clientId]);

		$this->db->delete(TABLE_PAYMENT_ITEMS, ['paymentId' => $id]);
		$this->db->delete(TABLE_PAYMENTS, ['id' => $id]);
	}
}
