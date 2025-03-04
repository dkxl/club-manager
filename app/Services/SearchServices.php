<?php
/**
 * Search.php
 * @author davidh
 * @package dk-appt
 */

namespace App\Services;

use DB;

class SearchServices
{

    /**
     * Find members by name
     * @param string $query
     * @return array[ stdClass{data,value}, stdClass{data,value}...]
     */
    public static function memberByName(string $query)
    {
        $query = $query . '%'; //append the wildcard symbol

        $sql = "SELECT id AS data, first_name||' '||last_name AS value
        		FROM members
        		WHERE ( first_name ILIKE :query
        		        OR last_name ILIKE :query
    	                OR first_name||' '||last_name ILIKE :query )
        		AND deleted_at IS NULL
        	    ORDER BY value ASC
        		LIMIT 40";

        return (array) DB::select($sql, ['query' => $query]);

    }


    /**
     * Find members by phone number
     * @param string $query
     * @return array[ stdClass{data,value}, stdClass{data,value}...]
     */
    public static function memberByPhone(string $query)
    {
        $query = preg_replace('/\s+/', '', $query) . '%'; //remove any whitespace; add wildcard

        $sql = "SELECT id AS data, first_name||' '||last_name AS value
    			FROM members
    			WHERE ( REPLACE (phone, ' ', '') LIKE :query )
    			AND deleted_at IS NULL
    			ORDER BY value ASC
    			LIMIT 40";

        return (array) DB::select($sql, ['query' => $query]);

    }


    /**
     * Find members by direct debit reference
     * @param string $query
     * @return array[ stdClass{data,value}, stdClass{data,value}...]
     */
    public static function memberByDirectDebit(string $query)
    {
        $query = preg_replace('/\D+/', '', $query) . '%'; //remove any non-digits; add wildcard

        $sql = "SELECT t2.id AS data, t2.first_name||' '||t2.last_name AS value
    			FROM direct_debits t1 LEFT JOIN members t2
    			ON t1.member_id = t2.id
    			WHERE CAST(t1.dd_ref AS TEXT) LIKE :query
    			ORDER BY value ASC
    			LIMIT 40";

        return (array) DB::select($sql, ['query' => $query]);

    }


}
