<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

// Entities
use App\Entity\Customer;
use App\Entity\CustomerLogin;

// Repositories
use App\Repository\CustomerRepository;
use App\Repository\CustomerLoginRepository;

class CustomerService {

	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	public function listCustomer() {

		$return = array();

		$customers = $this->em
					->getRepository(Customer::class)
					->findAll();

		foreach ($customers as $customer) {
			$tmp = array(
				'fullname' => $customer->getFirstName()." ".$customer->getLastName(),
				'email' => $customer->getEmail(),
				'country' => $customer->getCountry()
			);
			array_push($return, $tmp);
		}

		return $return;
	}

	public function getSpecificCustomer($id) {

		$customer = $this->em
					->getRepository(Customer::class)
					->findOneBy(array('id' => $id));

		$return = array();

		if (!is_null($customer)) {
			$return['fullname'] = $customer->getFirstName()." ".$customer->getLastName();
			$return['email'] = $customer->getEmail();
			$return['username'] = $customer->getCustomerLogin()->getUsername();
			$return['gender'] = $customer->getGender();
			$return['country'] = $customer->getCountry();
			$return['city'] = $customer->getCity();
			$return['phone'] = array(
				'telephone' => $customer->getPhone(),
				'mobile' => $customer->getMobile()
			);
		}

		return $return;
	}

}

?>