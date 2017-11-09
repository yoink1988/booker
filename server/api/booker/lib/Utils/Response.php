<?php
namespace Utils;

/**
 * Description of Response
 * Class for sending response to client
 * @author yoink
 */
class Response
{
	/**
	 * sends and error header
	 * @param int $h header status
	 */
    public static function ErrorResponse($h)
	{
        $header = array(
            400 => "HTTP/1.0 400 Bad Request",
            403 => "HTTP/1.0 403 Forbidden",
            404 => "HTTP/1.0 404 Not Found",
        );
		header($header[$h]);
    }

	/**
	 *
	 * @param int $h header status
	 */
    public static function SuccessResponse($h) {
        $header = array(
            200 => "HTTP/1.0 200 OK",
        );
		header($header[$h]);
    }

	/**
	 * outputs response data
	 * @param mixed $data array or string
	 */
	public static function doResponse($data)
	{
		print_r(json_encode($data));
	}
}
