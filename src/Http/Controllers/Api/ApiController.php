<?php

namespace Soda\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Soda\Cms\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends BaseController
{
    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'success';
    protected $statusCode = 200;
    protected $statusMessage;

    public function respondSuccess($data = null, $statusMessage = 'Success')
    {
        return $this->setStatusCode(Response::HTTP_OK)->setStatusMessage($statusMessage)->respond($data);
    }

    public function respond($data = null)
    {
        return $this->setStatusCode(Response::HTTP_OK)->buildResponse(static::STATUS_SUCCESS, $data);
    }

    protected function buildResponse($statusType, $data = null)
    {
        $response = [
            'meta' => $this->buildStatus($statusType),
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $this->getStatusCode());
    }

    protected function buildStatus($statusType)
    {
        $status = [
            'status' => $statusType,
            'code'   => $this->getStatusCode(),
        ];

        if ($message = $this->getStatusMessage()) {
            $status['message'] = $message;
        }

        return $status;
    }

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

    public function respondCreated($data = null, $statusMessage = 'Created')
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->setStatusMessage($statusMessage)->respond($data);
    }

    public function respondNotFound($data = null, $statusMessage = 'Not found')
    {
        return $this->respondWithError(Response::HTTP_NOT_FOUND, $statusMessage, $data);
    }

    public function respondWithError($statusCode, $statusMessage, $data = null)
    {
        return $this->setStatusCode($statusCode)->setStatusMessage($statusMessage)->buildResponse(static::STATUS_ERROR, $data);
    }

    public function respondUnauthorized($data = null, $statusMessage = 'Unauthorized')
    {
        return $this->respondWithError(Response::HTTP_UNAUTHORIZED, $statusMessage, $data);
    }

    public function respondRateLimited($data = null, $statusMessage = 'Too many requests')
    {
        return $this->respondWithError(Response::HTTP_TOO_MANY_REQUESTS, $statusMessage, $data);
    }

    public function respondNotAllowed($data = null, $statusMessage = 'Not allowed')
    {
        return $this->respondForbidden($data, $statusMessage);
    }

    public function respondForbidden($data = null, $statusMessage = 'Forbidden')
    {
        return $this->respondWithError(Response::HTTP_FORBIDDEN, $statusMessage, $data);
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array                    $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if ($request->expectsJson()) {
            return $this->respondInvalid($errors);
        }

        return parent::buildFailedValidationResponse($request, $errors);
    }

    public function respondInvalid($data = null, $statusMessage = 'Invalid input')
    {
        return $this->respondWithError(Response::HTTP_UNPROCESSABLE_ENTITY, $statusMessage, $data);
    }
}
