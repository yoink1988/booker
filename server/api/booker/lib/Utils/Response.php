<?php
namespace Utils;

/**
 * Description of Response
 *
 * @author yoink
 */
class Response
{
//    public static function ErrorResponse($h,$message)
    public static function ErrorResponse($h)
	{
        $header = array(
            400 => "HTTP/1.0 400 Bad Request",
            403 => "HTTP/1.0 403 Forbidden",
            404 => "HTTP/1.0 404 Not Found",
        );
		header($header[$h]);
//		print_r($message);
    }

    public static function SuccessResponse($h) {
        $header = array(
            200 => "HTTP/1.0 200 OK",
        );
		header($header[$h]);
    }

	public static function doResponse($data)
	{
		print_r(json_encode($data));
	}
}
