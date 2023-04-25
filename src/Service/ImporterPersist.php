<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

// Entities
use App\Entity\Customer;
use App\Entity\CustomerLogin;

// Repositories
use App\Repository\CustomerRepository;
use App\Repository\CustomerLoginRepository;

class ImporterPersist
{
	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	public function save($d) {

		if (empty($d) || is_null($d)) {
			return false;
		}

		// If login details do not exist in entries, return false
		if (!array_key_exists('login', $d)) {
			return false;
		}

		// Check if customer already exists. If yes, update. Else, Create new records
		$customer = $this->em
					->getRepository(Customer::class)
					->findOneByEmail($d['email']);

		if ($customer) {
			$customerLogin = $customer->getCustomerLogin();
		} else {
			// Create Customer
			$customer = new Customer();

			// Create Customer Login
			$customerLogin = new CustomerLogin();
		}

		// Set Customer Details
		$customer->setFirstName($d['first_name']);
		$customer->setLastName($d['last_name']);
		$customer->setEmail($d['email']);
		$customer->setGender(ucwords($d['gender']));
		$customer->setCountry($d['country']);
		$customer->setCity($d['city']);
		$customer->setPhone($d['phone']);
		$customer->setMobile($d['mobile']);

		$this->em->persist($customer);
		$this->em->flush();

		$customerLogin->setCustomer($customer);
		$customerLogin->setUuid($d['login']['uuid']);
		$customerLogin->setUsername($d['login']['username']);
		$customerLogin->setPassword($d['login']['md5']);

		$this->em->persist($customerLogin);
		$this->em->flush();

		return true;

	}
}

?>