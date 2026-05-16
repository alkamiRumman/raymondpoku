<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Site_model $site
 */
class Site extends MY_Controller
{
	public $path = '/site';

	function __construct()
	{
		parent::__construct();
		$this->load->model('Site_model', 'site');
	}

	public function index()
	{
		$this->ifLogin();
		$this->load->view('site/login');
	}

	function verify()
	{
//		return dnp($_POST);
		$username = $this->input->post("username");
		$pass = $this->input->post("password");
		if ($user = $this->site->getUser($username)) {
			if (md5($pass) == $user->password) {
				if (($user->type == 'User' && $user->expireDate > date('Y-m-d')) || $user->type == 'Admin') {
					$user = (array)$user;
					unset($user["password"]);
					$this->session->set_userdata("user", (object)$user);
					$this->session->set_flashdata('success', 'Login Succeed!');
					$this->ifLogin();
				} else {
					$this->session->set_flashdata('danger', 'Your package already expired!');
					redirect('site/index');
				}
			} else {
				$this->session->set_flashdata('danger', 'Wrong Username or Password..');
				redirect('site/index');
			}
		} else {
			$this->session->set_flashdata('danger', 'User not exists!');
			redirect('site/index');
		}
	}

	public function signup()
	{
		$this->ifLogin();
		$this->load->view('site/signup');
	}

	function checkEmail()
	{
		$cnt = $this->site->checkEmail($this->input->get('text'));
		return sendJson(["count" => $cnt]);
	}

	public function getPaymentMethodId()
	{
//		return json_encode($_POST);
		$token = $this->input->post('stripeToken');
		$arr['name'] = $fullName = $this->input->post('fullName');
		$email = $arr['username'] = $email = $this->input->post('username');
		$arr['password'] = md5($this->input->post('password'));
		$package = $ar['package'] = $arr['package'] = $this->input->post('package');
		$plan = '';
		switch ($package) {
			// my
//			case 1:
//				$plan = 'price_1PEXBfAN9B133qAX9E6CE8sY';
//				break;
//			case 6:
//				$plan = 'price_1PEXCBAN9B133qAXt3FfU17r';
//				break;
//			case 12:
//				$plan = 'price_1PEXCaAN9B133qAXZqo8wF3x';
//				break;

			//client
			case 1:
				$plan = 'price_1PLTgXLNr3A6FGWM53kk5xcL';
				break;
			case 6:
				$plan = 'price_1PLTibLNr3A6FGWMduKr3ayr';
				break;
			case 12:
				$plan = 'price_1PLTjnLNr3A6FGWMby9WWPho';
				break;

			default:
				$plan = '';
				break;
		}

//		return dnp($token);
		try {
			$customer = \Stripe\Customer::create(array(
				'email' => $email,
				'source' => $token,
			));

			$subscription = \Stripe\Subscription::create(array(
				'customer' => $customer->id,
				'items' => array(array('plan' => $plan)),
			));
//			$subscription->confirm();
			// Subscription created successfully
			$ar['sub_id'] = $arr['sub_id'] = $subscription->id;
			$this->site->save($arr);
			$ar['addedBy'] = $ar['userId'] = $this->db->insert_id();
			$this->site->savePackageHistory($ar);
			// Redirect to success page
			$this->session->set_flashdata('success', 'Your package successfully activated!');
			redirect($this->index());
		} catch (Exception $e) {
			// Handle errors
			$this->session->set_flashdata('danger', $e->getMessage());
			redirect($this->index());
		}

//		try {
//			// Get form data
//			$arr['name'] = $fullName = $this->input->post('fullName');
//			$arr['username'] = $email = $this->input->post('username');
//			$arr['password'] = md5($this->input->post('password'));
//			$ar['package'] = $arr['package'] = $package = $this->input->post('package');
//			$ar['expireDate'] = $arr['expireDate'] = $expireDate = date("Y-m-d", strtotime("+" . $package . " month", strtotime(date("Y-m-d"))));
//			$paymentMethodId = $this->input->post('payment_method_id'); // Payment method ID from Stripe.js
//
//			// Create a Payment Intent
//			$paymentIntent = \Stripe\PaymentIntent::create([
//				'amount' => $this->getPackageAmount($package), // Get package amount based on selected package
//				'currency' => $this->config->item('stripe_currency'),
//				'payment_method' => $paymentMethodId,
//				'description' => 'Name: ' . $fullName . ' Email: ' . $email . ' Expire date: ' . $expireDate,
//				'confirmation_method' => 'manual', // Use manual confirmation method
//			]);
//
//			// Confirm the Payment Intent
//			$paymentIntent->confirm();
//
//			// Process payment successful, save form data to database
//			$this->site->save($arr);
//			$ar['addedBy'] = $ar['userId'] = $this->db->insert_id();
//			$this->site->savePackageHistory($ar);
//			// Redirect to success page
//			echo json_encode(['status' => 'success', 'msg' => 'Successfully Paid.']);
//			redirect($_SERVER['HTTP_REFERER']);
//		} catch (\Stripe\Exception\CardException $e) {
//			// Payment failed due to card error
//			// Handle error, redirect to error page, or return error response
//			echo json_encode(['status' => false, 'msg' => $e->getMessage()]);
//		} catch (\Exception $e) {
//			// Payment failed due to other errors
//			// Handle error, redirect to error page, or return error response
//			echo json_encode(['status' => false, 'msg' => $e->getMessage()]);
//		}
	}

	function profile()
	{
		$this->popupView('/profile');
	}

	function update()
	{
		$name = $arr['name'] = $this->input->post('fullName');
		if ($this->input->post('username')) {
			$username = $arr['username'] = $this->input->post('username');
		}
		if ($this->input->post('password')) {
			$arr['password'] = md5($this->input->post('password'));
		}
		$config['upload_path'] = './images/' . getSession()->id;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['overwrite'] = true;

		if (!is_dir('images')) {
			mkdir('./images', 0777, true);
		}
		if (!is_dir('images/' . getSession()->id)) {
			mkdir('./images/' . getSession()->id, 0777, true);
		}
		$this->upload->initialize($config);
		$this->load->library('upload', $config);
		$this->upload->do_upload('profilePicture');
		$profile = $this->upload->data('file_name');

		if (!empty($_FILES['profilePicture']['name'])) {
			$arr['profilePicture'] = $profile;
			getSession()->profilePicture = $profile;
		}
// 		$this->site->update($arr, getSession()->id);
		getSession()->username = $username;
		getSession()->name = $name;
		$this->session->set_flashdata('success', 'Profile Updated!!');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function logout()
	{
		$this->session->unset_userdata('user');
		$this->session->set_flashdata('success', 'Successfully Logged Out!!');
		redirect(base_url());
	}

}
