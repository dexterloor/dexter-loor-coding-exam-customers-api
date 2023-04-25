<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

// Services
use App\Service\ImporterProcessor;
use App\Service\ImporterPersist;

/*
** @Service("importer.service")
*/
class Importer {

	private $client;
	private $max;
	private $nationality;
	private $importerProcessor;
	private $importerPersist;

	public function __construct(
		HttpClientInterface $randomUserApiClient,
		int $max,
		string $nationality,
		ImporterProcessor $importerProcessor,
		ImporterPersist $importerPersist

	) {
		$this->client = $randomUserApiClient;
		$this->max = $max;
		$this->nationality = $nationality;
		$this->importerProcessor = $importerProcessor;
		$this->importerPersist = $importerPersist;
	}

	/**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface   
     */
	public function import(\Closure $callback = null)
	{
		// Get data using scoped client HTTP requests
		$path = "/api";

		$response = $this->client->request('GET', $path, [
			'query' => [
				'results' => $this->max,
				'nat' => $this->nationality
			]
		]);

        $parsedResponse = $response->toArray();
        $customers = $parsedResponse['results'] ? $parsedResponse['results'] : [];

        if (empty($customers)) {
        	throw new Exception("No results.", 1);
        	return false;
        }

		// Use another service to process cURL response data
		$processed = $this->importerProcessor->process($customers);

		if (!$processed) {

			if (count($customers) < 100) {
				throw new Exception("Number of customers pulled is less than 100.", 1);	
				return false;
			}
			
			throw new Exception("Error in processing customer data.", 1);
			return false;
		}

		// Call another service to save processed data
		foreach($processed as $data) {

			if ($callback) {
				$callback($data);
			}

			$persisted = $this->importerPersist->save($data);

			if (!$persisted) {
				throw new Exception("Error in saving customer data.", 1);
				return false;
			}
		}

		// Return true if no errors encountered
		return true;
	}
}

?>