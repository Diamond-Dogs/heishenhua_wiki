# Http request

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/SemanticMediaWiki/http-request/CI)](https://github.com/SemanticMediaWiki/http-request/actions?query=workflow%3ACI)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/http-request/version.png)](https://packagist.org/packages/mediawiki/http-request)
[![Packagist download count](https://poser.pugx.org/mediawiki/http-request/d/total.png)](https://packagist.org/packages/mediawiki/http-request)

A minimalistic http/curl request interface that was part of the [Semantic MediaWiki][smw] code base and
is now being deployed as independent library.

This library provides:

- `HttpRequest` interface
- `CurlRequest` as cURL implementation of the `HttpRequest`
- `CachedCurlRequest` to support low-level caching on repeated `CurlRequest` requests
- `MultiCurlRequest` to make use of the cURL multi stack feature
- `SocketRequest` to create asynchronous socket connections

## Requirements

- PHP 8.0 or later

## Installation

The recommended installation method for this library is by adding the
dependency to your [composer.json][composer].

```json
{
	"require": {
		"mediawiki/http-request": "~2.0"
	}
}
```

## Usage

```php
use Onoi\HttpRequest\CurlRequest;
use Onoi\HttpRequest\Exception\BadHttpResponseException;
use Onoi\HttpRequest\Exception\HttpConnectionException;

class Foo {

	private $curlRequest = null;

	public function __constructor( CurlRequest $curlRequest ) {
		$this->curlRequest = $curlRequest;
	}

	public function doMakeHttpRequestTo( $url ) {

		$this->curlRequest->setOption( CURLOPT_URL, $url );

		if ( !$this->curlRequest->ping() ) {
			throw new HttpConnectionException( "Couldn't connect" );
		}

		$this->curlRequest->setOption( CURLOPT_RETURNTRANSFER, true );

		$this->curlRequest->setOption( CURLOPT_HTTPHEADER, array(
			'Accept: application/x-turtle'
		) );

		$response = $this->curlRequest->execute();

		if ( $this->curlRequest->getLastErrorCode() == 0 ) {
			return $response;
		}

		throw new BadHttpResponseException( $this->curlRequest );
	}
}
```
```php
$httpRequestFactory = new HttpRequestFactory();

$instance = new Foo( $httpRequestFactory->newCurlRequest() );
$response = $instance->doMakeHttpRequestTo( 'http://example.org' );

OR

$cacheFactory = new CacheFactory();

$compositeCache = $cacheFactory->newCompositeCache( array(
	$cacheFactory->newFixedInMemoryLruCache( 500 ),
	$cacheFactory->newDoctrineCache( new \Doctrine\Common\Cache\RedisCache() )
) );

$httpRequestFactory = new HttpRequestFactory( $compositeCache );
$cachedCurlRequest = $httpRequestFactory->newCachedCurlRequest();

// Responses for a request with the same signature (== same endpoint and same query
// content) will be cached if the request was successful for a specified 1 h (3600 sec)
$cachedCurlRequest->setOption( ONOI_HTTP_REQUEST_RESPONSECACHE_TTL, 60 * 60 );

$instance = new Foo( $cachedCurlRequest );
$response = $instance->doMakeHttpRequestTo( 'http://example.org' );
```

## Contribution and support

If you want to contribute work to the project you can:

* [File an issue](https://github.com/SemanticMediaWiki/http-request/issues)
* [Submit a pull request](https://github.com/SemanticMediaWiki/http-request/pulls)

A list of people who have made contributions in the past can be found [here][contributors].

### Tests

The library provides unit tests that covers the core-functionality normally run by the [continuous integration platform][ci]. Tests can also be executed manually using the PHPUnit configuration file found in the root directory.

## Release notes

### 2.0.0 (2022-12-13)
 - Increased minimum PHP requirement to 8.0

### 1.3.1 (2017-01-14)
 - Extended `SocketRequest` to match a possible TLS port

### 1.3.0 (2015-11-23)
 - Deprecated `CachedCurlRequest::setCachePrefix` and `CachedCurlRequest::setExpiryInSeconds` in favor of setting it via the option
   `ONOI_HTTP_REQUEST_RESPONSECACHE_PREFIX` and `ONOI_HTTP_REQUEST_RESPONSECACHE_TTL` (any change in the expiry will auto-invalidate existing items in cache)
 - Deprecated `CachedCurlRequest::isCached` in favor of `CachedCurlRequest::isFromCache`

### 1.2.0 (2015-11-09)
 - Added "wasAccepted" to the `SocketRequest` response output
 - Added option `ONOI_HTTP_REQUEST_FOLLOWLOCATION` to support resetting the URL location in case of a `301` HTTP response during a `SocketRequest::ping` request

### 1.1.0 (2015-09-12)
 - Renamed `AsyncCurlRequest` to `MultiCurlRequest`
 - Deprecated `MultiCurlRequest::setCallback` and to be replaced by `MultiCurlRequest::setOption( ONOI_HTTP_REQUEST_ON_COMPLETED_CALLBACK, ... )`
 - Added `SocketRequest` to create asynchronous socket connections

### 1.0.0 (2015-07-22, initial release)
 - Added the `HttpRequest` interface
 - Added the `CurlRequest` implementation
 - Added the `CachedCurlRequest` to extend the `CurlRequest` implementation
 - Added the `AsyncCurlRequest` implementation

## License

[GNU General Public License 2.0 or later][license].

[composer]: https://getcomposer.org/
[contributors]: https://github.com/SemanticMediaWiki/http-request/graphs/contributors
[license]: https://www.gnu.org/copyleft/gpl.html
[ci]: https://github.com/SemanticMediaWiki/http-request/actions
[smw]: https://github.com/SemanticMediaWiki/SemanticMediaWiki/
