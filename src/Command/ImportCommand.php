<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;

// Services
use App\Service\Importer;

class ImportCommand extends Command
{

	protected $importer;

	public function __construct(Importer $importer) {
		$this->importer = $importer;

		parent::__construct();
	}

	protected function configure() : void
	{
		$this->setName('import-customers')
			->setDescription('This command imports a list of users from the API URL defined in the .env file with the variable DATA_PROVIDER_URL. Change the variables DATA_PROVIDER_URL, DATA_PROVIDER_MAX, and DATA_PROVIDER_NATIONALITY in the .env file to update the API URL, number of results to pull and the Nationality filter, respectively.');
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Import started.');

		$progress = new ProgressBar($output, 100);
		$progress->start();

		$callback = function ($data) use ($progress) {
			$progress->advance();
		};

		$imported = $this->importer->import($callback);

		$output->writeln("\nImported customers successfully saved in the database.\n");
		$progress->finish();

		if ($imported) {
			return Command::SUCCESS;
		} else {
			return Command::FAILURE;
		}
	}

}

?>