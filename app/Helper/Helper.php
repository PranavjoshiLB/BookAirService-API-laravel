<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/8/2018
 * Time: 5:38 AM
 */
const NO_RESULT_FOUND = "NO_RESULT_FOUND";
const RESULT_FOUND = "OK";

if (! function_exists('ok')) {

    function ok($data, $msg = "Successful")
    {
        $data = [
            'statusCode' => \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            'status' => true,
            'msg' => $msg,
            'data' => $data,
            'extraStatus' => empty($data) ? NO_RESULT_FOUND : RESULT_FOUND,
        ];

        return response($data);
    }
}
if (! function_exists('fail')) {
    function fail($data, $msg = "Unsuccessful")
    {
        $data = [
            'statusCode' => \Symfony\Component\HttpFoundation\Response::HTTP_EXPECTATION_FAILED,
            'status' => false,
            'msg' => $msg,
            'data' => $data,
            'extra_data' => null,
        ];

        return response($data);
    }
}

if (! function_exists('formatDate')) {
    function formatDate(string $date)
    {
        $date = \DateTime::createFromFormat('dmy', $date);

        return $date->format('Y-m-d');
    }
}

if (! function_exists('formatTime')) {
    function formatTime(string $time)
    {
        $time = \DateTime::createFromFormat('Hi', $time);

        return $time->format('H:i');
    }
}
