<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UpdateProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds or update properties from the api into the database table';

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
     * @return int
     */
    public function handle()
    {
        echo "Update Properties Starts...\n";
        Log::info('Update Properties Starts...\n');

        try {
            //fetching details from api
            $client = new Client();
            $headers = [
                'Accept' => 'application/json'
            ];

            $api_key = env('API_KEY');

            $page_no = 1;
            $per_page = 100;

            $url = 'https://trial.craig.mtcserver15.com/api/properties/?api_key=' . $api_key . '&page[number]=' . $page_no . '&page[size]=' . $per_page;

            $res = $client->request('get', $url, [
                'headers' => $headers,
            ]);

            $details = json_decode($res->getBody(), true);
            $no_of_pages =  $details['last_page'];

            echo "Fetched details of api\n";
            Log::info("Fetched details of api\n");
    
        } catch (Exception $exception) {
            echo "Error in fetching details of api - " . $exception->getMessage() . "\n";
            Log::info("Error in fetching details of api - " . $exception->getMessage() . "\n");
        }

        for($i = 1; $i <= $no_of_pages; $i++){
            try {
                //fetching properties from api
                $client = new Client();
                $headers = [
                    'Accept' => 'application/json'
                ];
    
                $api_key = env('API_KEY');
    
                $page_no = $i;
                $per_page = 100;
    
                $url = 'https://trial.craig.mtcserver15.com/api/properties/?api_key=' . $api_key . '&page[number]=' . $page_no . '&page[size]=' . $per_page;
    
                $res = $client->request('get', $url, [
                    'headers' => $headers,
                ]);
    
                $properties = json_decode($res->getBody(), true);
    
                echo "Page No -  " . $properties['current_page']. " starts... \n";
                Log::info("Page No -  " . $properties['current_page']. " starts... \n");
            } catch (Exception $exception) {
                echo "Error in Property update " . $properties['current_page'] . " - " . $exception->getMessage() . "\n";
                Log::info("Error in Property update " . $properties['current_page'] . " - " . $exception->getMessage() . "\n");
            }

            //Looping through each property
            foreach ($properties['data'] as $api_property) {
                try{
                    $property = Property::where('uuid',$api_property['uuid'])->first();

                    if(!$property){
                        $property = new Property();
                        $property->uuid = $api_property['uuid'];
                    }
    
                    $property->county = $api_property['county'];
                    $property->country = $api_property['country'];
                    $property->town = $api_property['town'];
                    $property->description = $api_property['description'];
                    $property->address = $api_property['address'];
                    $property->image_full = $api_property['image_full'];
                    $property->image_thumbnail = $api_property['image_thumbnail'];
                    $property->latitude = $api_property['latitude'];
                    $property->longitude = $api_property['longitude'];
                    $property->num_bedrooms = $api_property['num_bedrooms'];
                    $property->num_bathrooms = $api_property['num_bathrooms'];
                    $property->price = $api_property['price'];
                    $property->property_type_id = $api_property['property_type_id'];
                    $property->type = $api_property['type'];
                    
                    $property->save();
    
                    echo "Property Updated for UUID - " . $api_property['uuid'] . "\n";
                    Log::info("Property Updated for UUID - " . $api_property['uuid'] . "\n");
                } catch (Exception $exception) {
                    echo "Error in Property update for UUID - " . $api_property['uuid'] . ' - ' . $exception->getMessage() . "\n";
                    Log::info("Error in Property update for UUID - " . $api_property['uuid'] . ' - ' . $exception->getMessage() . "\n");
                }
            }

            echo "Page No -  " . $properties['current_page']. " ends... \n";
            Log::info("Page No -  " . $properties['current_page']. " ends... \n");
        }

        echo "Update Properties command ends... \n";
        Log::info("Update Properties command ends... \n");

    }
}
