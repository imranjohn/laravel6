<?php
namespace App\Classes;

use stdClass;

class RestApi
{
    private static $pagination;


    public static function success($message = "", $output = [], $response_code = 200)
    {
        return response()->json(
            [
                "status" => true,
                "message" => $message,
                "data" => $output,
                "paging" => self::$pagination ?: new \stdClass()
            ]
        );
    }

    public static function successWithData($output = [], $message = "", $response_code = 200)
    {
        return response()->json(
            [
                "status" => true,
                "message" => $message,
                "data" => $output,
                "paging" => self::$pagination ?: new \stdClass()
            ]
        );
    }

    public static function error($message = "", $error_code = "", $reponse_code = 401)
    {
        return response()->json(["status" => false, "message" => $message, 'data' => new \stdClass()]);
    }

    public static function setPagination(\Illuminate\Pagination\LengthAwarePaginator $paginator)
    {
        self::$pagination = new \stdClass();
        
        self::$pagination->total_records = $paginator->total();
        self::$pagination->current_page  = $paginator->currentPage();
        self::$pagination->total_pages   = $paginator->lastPage();
        self::$pagination->limit         = (int)$paginator->perPage();

        return new static;
    }
}
