<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Implementation of this file has been influenced by Nyholm/psr7 and Laminas
 *
 * @link    https://github.com/Nyholm/psr7
 * @license https://github.com/Nyholm/psr7/blob/master/LICENSE
 * @link    https://github.com/laminas/laminas-diactoros
 * @license https://github.com/zendframework/zend-diactoros/blob/master/LICENSE.md
 */

namespace Phalcon\Proxy\Psr7\Http\Message;

use Phalcon\Http\Message\AbstractStream;
use Phalcon\Http\Message\Exception\RuntimeException;
use Psr\Http\Message\StreamInterface;

/**
 * Stream/file OO class
 *
 * @property resource|null   $handle
 * @property resource|string $stream
 */
class Stream extends AbstractStream implements StreamInterface
{
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     *
     * @return string
     */
    public function __toString()
    {
        return $this->doToString();
    }

    /**
     * Closes the stream and any underlying resources.
     */
    public function close()
    {
        $this->doClose();;
    }

    /**
     * Returns true if the end of the stream has been reached
     *
     * @return bool
     */
    public function eof()
    {
        return $this->doEof();
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws RuntimeException
     */
    public function getContents()
    {
        return $this->doGetContents();
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @param mixed|null $key
     *
     * @return array|mixed|null
     */
    public function getMetadata($key = null)
    {
        if (null !== $key) {
            $key = (string) $key;
        }
        return $this->doGetMetadata($key);
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null
     */
    public function getSize()
    {
        return $this->doGetSize();
    }

    /**
     * Returns whether the stream is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        return $this->doIsReadable();
    }

    /**
     * Returns whether the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable()
    {
        return $this->doIsSeekable();
    }

    /**
     * Returns whether the stream is writable.
     *
     * @return bool
     */
    public function isWritable()
    {
        return $this->doIsWritable();
    }

    /**
     * Read data from the stream.
     *
     * @param int $length
     *
     * @return string
     * @throws RuntimeException
     */
    public function read($length)
    {
        return $this->doRead((int) $length);
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @return void
     */
    public function rewind()
    {
        $this->doRewind();
    }

    /**
     * Seek to a position in the stream.
     *
     * @param int $offset
     * @param int $whence
     *
     * @return void
     * @throws RuntimeException
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->doSeek($offset, $whence);
    }

    /**
     * Sets the stream - existing instance
     *
     * @param mixed  $stream
     * @param string $mode
     */
    public function setStream($stream, string $mode = "rb"): void
    {
        $handle  = $stream;
        $warning = false;
        if (true === is_string($stream)) {
            set_error_handler(
                function () use (&$warning) {
                    $warning = true;
                },
                E_WARNING
            );

            $handle = $this->phpFopen($stream, $mode);

            restore_error_handler();
        }

        if (
            true === $warning ||
            true !== is_resource($handle) ||
            "stream" !== get_resource_type($handle)
        ) {
            throw new RuntimeException(
                "The stream provided is not valid (string/resource) or could not be opened."
            );
        }

        $this->handle = $handle;
        $this->stream = $stream;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int
     * @throws RuntimeException
     */
    public function tell()
    {
        return $this->doTell();
    }

    /**
     * Write data to the stream.
     *
     * @param string $data
     *
     * @return int
     * @throws RuntimeException
     */
    public function write($string)
    {
        return $this->doWrite($string);
    }
}
