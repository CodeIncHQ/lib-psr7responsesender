<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     23/02/2018
// Time:     13:44
// Project:  lib-psr7responsesenders
//
declare(strict_types = 1);
namespace CodeInc\Psr7ResponseSender;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseSender
 *
 * @package CodeInc\Psr7ResponseSender
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ResponseSender implements ResponseSenderInterface {
	/**
	 * ResponseSender constructor.
	 *
	 * @param bool|null $removeNativeHeaders
	 */
	public function __construct(bool $removeNativeHeaders = null)
	{
		if ($removeNativeHeaders === null || $removeNativeHeaders) {
			header_register_callback([$this->__construct(), "removeNativeHeaders"]);
		}
	}

	/**
	 * @inheritdoc
	 * @throws ResponsSenderException
	 */
	public function send(ResponseInterface $response):void
	{
		$this->sendResponseHeaders($response);
		$this->sendReponseBody($response);
	}

	/**
	 * Removes all native PHP headers.
	 */
	public function removeNativeHeaders():void
	{
		foreach (headers_list() as $header) {
			header_remove(explode(":", $header)[0]);
		}
	}

	/**
	 * Sends the response headers.
	 *
	 * @param ResponseInterface $response
	 * @throws ResponsSenderException
	 */
	protected function sendResponseHeaders(ResponseInterface $response):void
	{
		// checking
		if (headers_sent()) {
			throw new ResponsSenderException("A response has already been sent to the web browser",
				$this);
		}

		// removing php headers

		// sending
		header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} "
			."{$response->getReasonPhrase()}", true);
		foreach ($response->getHeaders() as $header => $values) {
			header("$header: ".implode(", ", $values));
		}
	}

	/**
	 * Sends the response body.
	 *
	 * @param ResponseInterface $response
	 */
	protected function sendReponseBody(ResponseInterface $response):void
	{
		$body = $response->getBody();
		while (!$body->eof()) {
			if ($line = \GuzzleHttp\Psr7\readline($body, 1024)) {
				echo $line;
				flush();
			}
		}
	}
}