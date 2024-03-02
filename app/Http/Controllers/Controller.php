<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @description Called when the data is found to the desired request
     * @param $data
     * @param $message
     * @param int $status
     * @return JsonResponse
     */
    public function responseJsonSuccess(string $message = '', $data = [], array $additional = []) : JsonResponse
    {
        return response()->json(
            array_merge([
                'status'  => 1,
                'message' => $message,
                'data'    => $data
            ],
                $additional),
            Response::HTTP_OK
        );
    }

    /**
     * @description Called when data is returned, but the data returned is unexpected
     * @param string $message
     * @return JsonResponse
     */
    public function responseJsonFail($message = '')
    {
        return response()->json(
            [
                'status' => 0,
                'message' => $message,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function responseJsonError($message)
    {
        return response()->json(
            [
                'status' => 0,
                'message' => $message
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function responseJsonErrorWithCode($code, $message)
    {
        return response()->json(
            [
                'status' => 0,
                'error_code' => $code,
                'message' => $message
            ],
            Response::HTTP_OK
        );
    }
    /**
     * @param null $url
     * @return JsonResponse
     */
    public function responseUnauthorized($url = null)
    {
        return response()->json(
            [
                'url' => $url,
                'message' => "Unauthorized"
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @return JsonResponse
     */
    public function responseDuplicateData()
    {
        return response()->json(
            [
                'status' => 0,
                'message' => __('message.data_already_exists')
            ],
            Response::HTTP_CONFLICT
        );
    }

    /**
     * @param $url
     * @param $message
     * @return JsonResponse
     */
    public function responseMovedPermanently($url, $message)
    {
        return response()->json(
            [
                'url' => $url,
                'message' => $message
            ],
            Response::HTTP_MOVED_PERMANENTLY
        );
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function responseBadRequest($message)
    {
        return response()->json(
            [
                'message' => $message
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function responseNotFound($message)
    {
        return response()->json(
            [
                'message' => $message
            ],
            Response::HTTP_NOT_FOUND
        );
    }
}
