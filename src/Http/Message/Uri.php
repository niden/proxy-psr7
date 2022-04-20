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

declare(strict_types=1);

namespace Phalcon\Proxy\Psr7\Http\Message;

use Phalcon\Http\Message\Exception\InvalidArgumentException;
use Phalcon\Http\Message\AbstractUri;
use Psr\Http\Message\UriInterface;

use function is_float;
use function is_numeric;
use function sprintf;
use function strpos;

/**
 * Uri
 *
 * @property string   $fragment
 * @property string   $host
 * @property string   $pass
 * @property int|null $port
 * @property string   $query
 * @property string   $scheme
 * @property string   $userInfo
 */
final class Uri extends AbstractUri implements UriInterface
{
    /**
     * Return the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters
     *
     * @return string
     */
    public function __toString()
    {
        return $this->doToString();
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority()
    {
        return $this->doGetAuthority();
    }

    /**
     * Returns the fragment of the URL
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Returns the path of the URL
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard
     * port used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Returns the query of the URL
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment
     *
     * @return UriInterface
     */
    public function withFragment($fragment)
    {
        return $this->cloneInstance(
            $this->filterFragment($fragment),
            "fragment"
        );
    }

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host
     *
     * @return UriInterface
     * @throws InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host)
    {
        $this->checkStringParameter($host, "host");

        return $this->cloneInstance(mb_strtolower($host), "host");
    }

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If an HTTP path is intended to be host-relative rather than path-relative
     * then it must begin with a slash ("/"). HTTP paths not starting with a
     * slash are assumed to be relative to some base path known to the
     * application or consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path
     *
     * @return UriInterface
     * @throws InvalidArgumentException for invalid paths.
     */
    public function withPath($path)
    {
        $this->checkStringParameter($path, "path");

        if (
            false !== strpos($path, "?") ||
            false !== strpos($path, "#")
        ) {
            throw new InvalidArgumentException(
                "Path cannot contain a query string or fragment"
            );
        }

        return $this->cloneInstance($this->filterPath($path), "path");
    }

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param int|null $port
     *
     * @return UriInterface
     * @throws InvalidArgumentException for invalid ports.
     */
    public function withPort($port)
    {
        if ($port !== null) {
            if (true !== is_numeric($port) && true !== is_float($port)) {
                throw new InvalidArgumentException(
                    sprintf("Invalid port [%s] specified", $port)
                );
            }

            $port = (int) $port;
        }

        return $this->cloneInstance($this->filterPort($port), "port");
    }

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query
     *
     * @return UriInterface
     * @throws InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query)
    {
        $this->checkStringParameter($query, "query");

        if (false !== strpos($query, "#")) {
            throw new InvalidArgumentException(
                "Query cannot contain a URI fragment"
            );
        }

        return $this->cloneInstance($this->filterQuery($query), "query");
    }

    /**
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme
     *
     * @return UriInterface
     * @throws InvalidArgumentException for invalid schemes.
     * @throws InvalidArgumentException for unsupported schemes.
     */
    public function withScheme($scheme)
    {
        $this->checkStringParameter($scheme, "scheme");

        return $this->cloneInstance($this->filterScheme($scheme), "scheme");
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string      $user
     * @param string|null $password
     *
     * @return UriInterface
     */
    public function withUserInfo($user, $password = null)
    {
        $this->checkStringParameter($user, "user");

        $userInfo = $this->filterUserInfo($user);
        if (null !== $password) {
            $this->checkStringParameter($password, "password");

            $userInfo .= ":" . $this->filterUserInfo($password);
        }

        return $this->cloneInstance($userInfo, "userInfo");
    }
}
