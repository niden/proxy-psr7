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

use Phalcon\Http\Message\AbstractUploadedFile;
use Phalcon\Proxy\Psr7\Http\Message\Exception\RuntimeException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * UploadedFile class
 *
 * @property bool                        $alreadyMoved
 * @property string|null                 $clientFilename
 * @property string|null                 $clientMediaType
 * @property int                         $error
 * @property string                      $fileName
 * @property int|null                    $size
 * @property StreamInterface|string|null $stream
 */
final class UploadedFile extends AbstractUploadedFile implements UploadedFileInterface
{
    /**
     * @return string|null
     */
    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    /**
     * @return string|null
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

    /**
     * @return int|null
     */
    public function getError()
    {
        return $this->error;
    }

    public function getSize()
    {
        return $this->size;
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native
     * PHP stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in
     * a native PHP stream wrapper to work with such functions).
     *
     * If the moveTo() method has been called previously, this method MUST
     * raise an exception.
     *
     * @return StreamInterface Stream representation of the uploaded file.
     * @throws RuntimeException in cases when no stream is available or can be created.
     */
    public function getStream()
    {
        $this->checkGetStream();


        if (!($this->stream instanceof StreamInterface)) {
            $this->stream = new Stream($this->fileName);
        }

        return $this->stream;
    }

    public function moveTo($targetPath)
    {
        $this->doMoveTo($targetPath);
    }

    /**
     * @return string
     */
    protected function getStreamClass(): string
    {
        return "Phalcon\\Proxy\\Psr7\\Http\\Message\\Stream";
    }

    /**
     * @return string
     */
    protected function getStreamInterface(): string
    {
        return "Psr\\Http\\Message\\StreamInterface";
    }
}
