<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Symfony\Component\Console\Input\InputArgument;
use Pimcore\Model\DataObject\FootballPlayer;
use \Pimcore\Model\Element\Service;





class ImportCommand extends Command
{
    protected static $defaultName = 'my:import-command';

    protected function configure()
    {
        $this->setDescription('Hier können Sie neue Daten im ExcelFormat importieren.');
        $this->addArgument('file', InputArgument::REQUIRED, 'Pfad zur Excel-Datei');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        $headers = [];
        $data = [];

        $test = new FootballPlayer();
        $test->save();

        // Lesen der Spaltenüberschriften
        foreach (range('A', $highestColumn) as $column) {
            $headers[] = $worksheet->getCell($column . '1')->getValue();
        }

        // Lesen der Datenzeilen
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($headers as $key => $header) {
                $rowData[$header] = $worksheet->getCellByColumnAndRow($key + 1, $row)->getValue();
            }
            $data[] = $rowData;
        }


        // Konvertieren der Daten in FootballPlayer-Objekte
        $footballPlayers = [];
        foreach ($data as $row) {
            $footballPlayer = new FootballPlayer();
            // $footballPlayer->setKey(\Pimcore\Model\Element\Service::getValidKey(uniqid(), 'object'));
            $footballPlayer->setModificationDate(time());

            // $output->writeln($row['Position']);

            $footballPlayer->setName($row['Name']);
            $footballPlayer->setNumber($row['Spielernummer']);
            $footballPlayer->setAge($row['Alter']);
            $footballPlayer->setPosition($row['Position']);
            // $footballPlayer->save();

            $footballPlayers[] = $footballPlayer;

            $service = new Service();
            $service->create($footballPlayer);

        }

        // Speichern der Daten in der Datenbank
        // foreach ($footballPlayers as $footballPlayer) {
        //     // $footballPlayer->save();
        // }

        $output->writeln('Daten erfolgreich importiert.');

        return Command::SUCCESS;
    }
}