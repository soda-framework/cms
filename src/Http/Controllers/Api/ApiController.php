<?php

namespace Soda\Cms\Http\Controllers\Api;

use Illuminate\Http\Response;
use Soda\Cms\Http\Controllers\BaseController;

abstract class ApiController extends BaseController
{
    protected $statusCode = 200;
    protected $statusMessage;

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

    public function respondCreated($message = 'Created')
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->setStatusMessage($message)->respond();
    }

    public function respondNotFound($message = 'Not Found')
    {
        return $this->respondWithError(Response::HTTP_NOT_FOUND, $message);
    }

    public function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondWithError(Response::HTTP_UNAUTHORIZED, $message);
    }

    public function respondRateLimited($message = 'Rate Limited')
    {
        return $this->respondWithError(Response::HTTP_TOO_MANY_REQUESTS, $message);
    }

    public function respondNotAllowed($message = 'Not Allowed')
    {
        return $this->respondWithError(Response::HTTP_FORBIDDEN, $message);
    }

    public function respondInvalid($message = 'Invalid Input')
    {
        return $this->respondWithError(Response::HTTP_UNPROCESSABLE_ENTITY, $message);
    }

    public function respond($data = [])
    {
        return $this->setStatusCode(Response::HTTP_OK)->buildResponse(static::STATUS_SUCCESS, ['data' => $data]);
    }

    protected function respondWithError($code, $message)
    {
        return $this->setStatusCode($code)->setStatusMessage($message)->buildResponse(static::STATUS_ERROR);
    }

    protected function buildResponse($statusType, array $data = [])
    {
        $response = [
            'status' => $this->buildStatus($statusType),
        ];

        if (count($data)) {
            $response['data'] = $data;
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
