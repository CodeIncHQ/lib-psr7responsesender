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
// Time:     13:48
// Project:  lib-psr7responsesenders
//
declare(strict_types = 1);
namespace CodeInc\Psr7ResponseSender;
use Throwable;


/**
 * Class ResponsSenderException
 *
 * @package CodeInc\PSR7ResponseSender
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ResponsSenderException extends \Exception {
	/**
	 * @var ResponseSenderInterface
	 */
	private $responseSender;

	/**
	 * ResponsSenderException constructor.
	 *
	 * @param string $message
	 * @param ResponseSenderInterface $responseSender
	 * @param int|null $code
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, ResponseSenderInterface $responseSender, ?int $code = null,
		?Throwable $previous = null)
	{
		$this->responseSender = $responseSender;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return ResponseSenderInterface
	 */
	public function getResponseSender():ResponseSenderInterface
	{
		return $this->responseSender;
	}
}