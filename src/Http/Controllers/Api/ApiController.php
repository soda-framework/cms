<?php

namespace Soda\Cms\Http\Controllers\Api;

use Illuminate\Http\Response;
use Soda\Cms\Http\Controllers\BaseController;

abstract class ApiController extends BaseController
{
    protected $statusCode = 200;
    protected $statusMessage;

    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'success';

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @param string $statusMessage
     *
     * @return $this
     */
    public function setStatusMessage($statusMessage)
    {
        $this->statusMessage = $statusMessage;

        return $this;
    }

    public function respondSuccess($message = 'Success', $extraData = null)
    {
        return $this->setStatusCode(Response::HTTP_OK)->setStatusMessage($message)->respond($extraData);
    }

    public function respondCreated($message = 'Created', $extraData = null)
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->setStatusMessage($message)->respond($extraData);
    }

    public function respondNotFound($message = 'Not Found', $extraData = null)
    {
        return $this->respondWithError(Response::HTTP_NOT_FOUND, $message, $extraData);
    }

    public function respondUnauthorized($message = 'Unauthorized', $extraData = null)
    {
        return $this->respondWithError(Response::HTTP_UNAUTHORIZED, $message, $extraData);
    }

    public function respondRateLimited($message = 'Rate Limited', $extraData = null)
    {
        return $this->respondWithError(Response::HTTP_TOO_MANY_REQUESTS, $message, $extraData);
    }

    public function respondNotAllowed($message = 'Not Allowed', $extraData = null)
    {
        return $this->respondWithError(Response::HTTP_FORBIDDEN, $message, $extraData);
    }

    public function respondInvalid($message = 'Invalid Input', $extraData = null)
    {
        return $this->respondWithError(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $extraData);
    }

    public function respond($data = [])
    {
        return $this->setStatusCode(Response::HTTP_OK)->buildResponse(static::STATUS_SUCCESS, $data);
    }

    protected function respondWithError($code, $message, $extraData = null)
    {
        return $this->setStatusCode($code)->setStatusMessage($message)->buildResponse(static::STATUS_ERROR, $extraData);
    }

    protected function buildResponse($statusType, array $extraData = [])
    {
        $response = [
            'status' => $this->buildStatus($statusType),
        ];

        if (count($extraData)) {
            $response = array_merge($response, $extraData);
        }

        return response()->json(compact('response'), $this->getStatusCode());
    }

    protected function buildStatus($statusType)
    {
        $status = [
            'type' => $statusType,
            'code' => $this->getStatusCode(),
        ];

        if ($message = $this->getStatusMessage()) {
            $status['message'] = $message;
        }

        return $status;
    }

}
