<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostcodeRequest;
use App\Services\AddressLookupServices;
use http\Env\Response;


class PostcodeController extends BaseController
{
    // Interface to getaddress.io

    public function show(PostcodeRequest $postcode)
    {
        // lookup the postcode and return an array of house options and address data
        // first element of the array is the pretty printed postcode
        $addresses = new AddressLookupServices($postcode)->lookup();

        if ($addresses){
              return response()->json($addresses, $addresses->getStatusCode());
        } else {
            return response()->json(['errors' => ['Postcode lookup failed']], 422);
        }

    }


}
