<?php

declare(strict_types=1);

namespace Zaxbux\BackblazeB2\Class;

use function sizeof;
use function rawurlencode;

//use ArrayAccess;
//use JsonSerializable;
use RuntimeException;

//use Zaxbux\BackblazeB2\Trait\ProxyArrayAccessToProperties;

/**
 * @link https://www.backblaze.com/b2/docs/files.html#fileInfo
 * 
 * @package Zaxbux\BackblazeB2\B2\Object
 */
abstract class ObjectInfoBase {
//final class FileInfo implements ArrayAccess, JsonSerializable {
	//use ProxyArrayAccessToProperties;

	public const HEADER_PREFIX = 'X-Bz-Info-';

	/** @var array */
	private $data;

	public function __construct(array $data = [])
	{
		if (sizeof($data) > 10) {
			throw new RuntimeException('Custom file information can only be up to 10 key/value pairs.');
		}

		$this->data = $data;
	}

	public function empty(): void
	{
		$this->data = [];
	}

	public function unset(string $key): ObjectInfoBase
	{
		unset($this->data[$key]);

		return $this;
	}

	public function set(string $key, string $value): ObjectInfoBase
	{
		if ($this->size() >= 10) {
			throw new RuntimeException('Custom object information can only be up to 10 key/value pairs.');
		}

		$this->data[$key] = $value;

		return $this;
	}

	public function get(?string $key = null): string|array
	{
		if ($key === null) {
			return $this->data;
		}

		return $this->data[$key];
	}

	public function size(): int
	{
		return sizeof($this->data);
	}

	/**
	 * Get the file info as headers for the B2 API.
	 * 
	 * @return array Array of the file info, keys beginning with `X-Bz-Info-`.
	 */
	public function getHeaders(): array
	{
		$headers = [];

		foreach ($this->data as $key => $value) {
			$headers[static::HEADER_PREFIX . $key] = rawurlencode($value);
		}

		return $headers;
	}

	public static function fromArray(array $data): ObjectInfoBase
	{
		return static($data);
	}

	/** @inheritdoc */
	/*
	public function jsonSerialize(): array
	{
		return [];
	}
	*/
}