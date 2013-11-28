<?php

namespace POData\OperationContext\Symfony2;

use POData\Common\ODataConstants;
use POData\OperationContext\IHTTPRequest;
use POData\OperationContext\IOperationContext;
use POData\OperationContext\Web\OutgoingResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Symfony2OperationContext implements IOperationContext
{

	/**
	 * @var Symfony2Request;
	 */
	protected $request;

	protected $response;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request){
		$this->request = new Symfony2Request($request);
		$this->response = new OutgoingResponse();
	}

	/**
	 * Gets the Web request context for the request being sent.
	 *
	 * @return OutgoingResponse reference of OutgoingResponse object
	 */
	public function outgoingResponse()
	{
		return $this->response;
	}


	/**
	 * Gets the Web request context for the request being received.
	 *
	 * @return IHTTPRequest reference of IncomingRequest object
	 */
	public function incomingRequest()
	{
		return $this->request;
	}


	/**
	 * Write the response (header and response body).
	 *
	 * @param OutgoingResponse &$outGoingResponse Headers and streams to output.
	 * @param Request &$request The original Symfony Request
	 */
	public function getResponse()
	{
		$outGoingResponse = $this->response;
		$request = $this->request;

		$headers = $outGoingResponse->getHeaders();
		$status_code = (isset($headers[ODataConstants::HTTPRESPONSE_HEADER_STATUS])) ? $headers[ODataConstants::HTTPRESPONSE_HEADER_STATUS] : 200;
		unset($headers[ODataConstants::HTTPRESPONSE_HEADER_STATUS]);

		$response = new Response(trim($outGoingResponse->getStream()), $status_code, array_filter($headers));
		$response->prepare($request->getRequest());

		return $response;
	}
}