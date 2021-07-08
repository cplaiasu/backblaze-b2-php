<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use Zaxbux\BackblazeB2\Object\Bucket;
use Zaxbux\BackblazeB2\Object\Bucket\BucketType;
use Zaxbux\BackblazeB2\Object\File;
use Zaxbux\BackblazeB2\Object\Key;
use Zaxbux\BackblazeB2\Response\BucketList;
use Zaxbux\BackblazeB2\Response\FileList;
use Zaxbux\BackblazeB2\Response\KeyList;

class ListTest extends TestCase
{
	public static function testFileList()
	{
		$fileList = new FileList([
			[
				'fileId' => 'id1',
			],
			new File('id2'),
		]);

		$fileList->append(new File('id3'));

		static::assertInstanceOf(File::class, $fileList->current());
		static::assertEquals('id1', $fileList->current()->getId());

		$fileList->next();

		static::assertInstanceOf(File::class, $fileList->current());
		static::assertEquals('id2', $fileList->current()->getId());

		$fileList->next();

		static::assertInstanceOf(File::class, $fileList->current());
		static::assertEquals('id3', $fileList->current()->getId());
	}

	public static function testBucketList()
	{
		$fileList = new BucketList([
			[
				'bucketId' => 'id1',
				'bucketName' => 'name1',
				'bucketType' => BucketType::PRIVATE,
			],
			new Bucket('id2', 'name2', BucketType::PRIVATE),
		]);

		$fileList->append(new Bucket('id3', 'name3', BucketType::PRIVATE));

		static::assertInstanceOf(Bucket::class, $fileList->current());
		static::assertEquals('id1', $fileList->current()->getId());

		$fileList->next();

		static::assertInstanceOf(Bucket::class, $fileList->current());
		static::assertEquals('id2', $fileList->current()->getId());

		$fileList->next();
		
		static::assertInstanceOf(Bucket::class, $fileList->current());
		static::assertEquals('id3', $fileList->current()->getId());
	}

	public static function testKeyList()
	{
		$fileList = new KeyList([
			[
				'name' => 'name1',
			],
			new Key('name2'),
		]);

		$fileList->append(new Key('name3'));

		static::assertInstanceOf(Key::class, $fileList->current());
		static::assertEquals('name1', $fileList->current()->getName());

		$fileList->next();

		static::assertInstanceOf(Key::class, $fileList->current());
		static::assertEquals('name2', $fileList->current()->getName());

		$fileList->next();
		
		static::assertInstanceOf(Key::class, $fileList->current());
		static::assertEquals('name3', $fileList->current()->getName());
	}
}
