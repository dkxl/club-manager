<?php

namespace App\Http\Controllers;

use App\Services\SearchServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * SearchController
 * Handles name and phone number searches etc
 * @package App\Http\Controllers
 */
class SearchController extends BaseController
{

    /**
     * Autocomplete helper for member searches
     *
     * If the first character is numeric (digit, plus or minus sign), try
     * a phone number search; Otherwise try a name search
     * If still no results, try a Direct Debit search;
     *
     * Note: by default, the autocomplete.js stops looking if the first query returned no results.
     * So if 'Fr' returns no results, autocomplete will not try looking for 'Fre'.
     * Set preventBadQueries = false in the autocomplete constructor to override this.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function member(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|alpha_num',
        ]);

        if ( is_numeric($validated['query']) ) {
            $result['suggestions'] = SearchServices::memberByPhone($validated['query']);
        } else { //try a name search
            $result['suggestions'] = SearchServices::memberByName($validated['query']);
        }

        return $result;  // result array is automatically converted to JSON by Laravel

    }


}
