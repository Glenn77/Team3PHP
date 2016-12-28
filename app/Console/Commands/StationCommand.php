<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Station;
use App\Log;
use Exception;

use XMLWriter;

class StationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexStations:xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an xml file of all available stations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = storage_path('xmls/stations.xml');

        $stations = \App\Station::all();

        $bar = $this->output->createProgressBar(count($stations));

        $timestamp = \Carbon\Carbon::now()->timestamp;

        $log = new Log();
        $log->LogID = $timestamp;
        $log->CreatedAt = $timestamp;
        $log->LogOrigin = "indexStations:xml";

        try
        {
            $xml = new XMLWriter();
            $xml->openUri($path);
            $xml->startDocument('1.0');
            $xml->setIndent(true);

            $xml->startElement('Stations');

            foreach ($stations as $station) {

                $xml->startElement('Station');

                $xml->writeElement('StationID', $station->StationID);
                $xml->writeElement('Name', $station->Name);
                $xml->writeElement('AddressID', $station->AddressID);

                $xml->endElement();
                $bar->advance();
            }

            $xml->endElement();
            $xml->endDocument();

            $xml->flush();

            $log->LogMessage = "Stations succesfully indexed to \"" . $path . "\".";
            $this->info("Stations succesfully indexed to \"" . $path . "\".");
        }
        catch(Exception $e)
        {
            $log->LogMessage = "Stations NOT succesfully indexed to \"" . $path . "\". \n" . $e->getMessage();
            $this->info("Stations NOT succesfully indexed to \"" . $path . "\". \n" . $e->getMessage());
        }
        finally
        {
            $log->save();
            $bar->finish();
        }
    }
}
