<?php
/**
 * AddressLookupServices.php
 * Models the getaddress postcode lookup service
 *
 * Based on petelawrence/getaddress
 *
 * @author davidh
 * @package dk-appt
 */
namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class AddressLookupServices {

    private $apiKey = '';
    protected $postcode = '';
    protected $status_code = 0; //last http status

    protected $rules = [
        'postcode' => 'regex:/^[A-Z]{1,2}[0-9][0-9A-Z]?[0-9][A-Z]{2}$/'
    ];


    public function __construct($postcode)
    {
        $this->apiKey = config('club.postcode_api_key');
        //normalise the postcode - uppercase, no whitespace
        $this->postcode = preg_replace('/[^A-Z0-9]/', '', strtoupper($postcode));
    }


    public function getPrettyPostcode() {
        return preg_replace('/([A-Z0-9]{3})$/', ' \1', $this->postcode);  //with one space
    }

    public function getStatusCode() {
        return $this->status_code;
    }

    /**
     * Queries getaddress.io for houses with the given postcode
     *
     * @param string $postcode The postcode to return houses for
     * @return array
     * @throws Exception
     */
    public function lookup()
    {

        //Create a new Guzzle client
        $client = new Client(
            [
                'base_uri' => 'https://api.getAddress.io/v2/uk/',
                'timeout' => 30.0,
            ]
        );

        //Perform the query using HTTP basic auth, 'api-key' is the username and the key is the password
        try {
            $response = $client->request('GET', $this->postcode, [
                'auth' => ['api-key', config('club.postcode_api_key')],
                'headers' => ['Accept' => 'application/json'],
            ]);

            $this->status_code = 200;
            $result = $this->parseResponse($response->getBody()->getContents());

        } catch (RequestException $e) {
            $this->status_code = $e->getResponse()->getStatusCode();

            switch ($this->status_code) {
                case 401:
                    $result = [
                                'postcode' => $this->getPrettyPostcode(),
                                'error' => 'getaddress.io auth failed'
                              ];
                    break;

                case 404:
                    $result = [
                                'postcode' => $this->getPrettyPostcode(),
                                'error' => 'Postcode not found'
                              ];
                    break;

                default:
                    //Default exception
                    $result = [
                                'postcode' => $this->getPrettyPostcode(),
                                'error' => 'getaddress.io error'
                              ];

            }

        } //catch

        return $result;
    }


    /**
     * Parse the response and return ready to form an html option list drop down

     * Each element in the JSON response from getaddress.io is a comma separated house address:
     *  0       1     2       3     4         5            6
     * line1, line2, line3, line3, locality, postal town, county
     *
     * @param $response
     * @return array ["postcode" => postcode, "addresses" => [["option" => option, "data" =>[$data]],...]]
     */
    public function parseResponse($response)
    {
        //Convert the response from JSON into an object
        $responseObj = json_decode($response);

        $getAddressResponse = [
                               'postcode' => $this->getPrettyPostcode(),
                               'addresses' => []
                              ];

        //Set the address fields
        foreach ($responseObj->Addresses as $addressLine) {
            // explode the getaddress.io response
            $parts = explode(',', $addressLine);

            foreach ($parts as &$part) {
                $part = trim($part);
            }

            $address_1 = $parts[0];
            $address_2 = $parts[1];
            $address_3 = $parts[2];
            $address_4 = $parts[3];
            $town = ($parts[4]) ? $parts[4] : $parts[5]; //prefer locality to postal town, if present
            $county = $parts[6];

            // Assemble into an option list friendly format. Use array_filter to omit empty elements
            $option = implode(', ', array_filter([$address_1, $address_2, $address_3, $address_4, $town]));

            // And attach the data elements to the option
            $data  = ['address_1' => $address_1,
                      'address_2' => $address_2,
                      'address_3' => $address_3,
                      'address_4' => $address_4,
                      'town' => $town,
                      'county' => $county
                    ];
            $getAddressResponse['addresses'][] = ['option' => $option, 'data' => $data];

        }

        return $getAddressResponse;
    }

}
