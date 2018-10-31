Favicons
========

Free Favicons API with Super Fast Global CDN, proxying favicon from a domain. This is the code behind `favicons.marsble.com`.

 - See _Favicons_ on [Staticaly](https://www.staticaly.com/favicons)
 - Visit [favicons.marsble.com](https://favicons.marsble.com) to learn more about _Favicons_.

Usage
-----

~~~ .txt
GET `https://favicons.marsble.com/:domain`
~~~

**Example:**

 - [https://favicons.marsble.com/marsble.com](https://favicons.marsble.com/marsble.com)
 - [https://favicons.marsble.com/github.com](https://favicons.marsble.com/github.com)

**Result:**

![Marsble](https://favicons.marsble.com/marsble.com) &middot; ![GitHub](https://favicons.marsble.com/github.com)

Miscellaneous
-------------

### Get as Raw Blob

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?raw`
~~~

### Get as Base64 String

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?base64`
~~~

### Get as JSON

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?json`
~~~

### Get as HTML

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?html`
~~~

### Get as XHTML

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?xhtml`
~~~

### Force to Use SSL

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?ssl=1`
~~~

### Disable Cache

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?cache=0`
~~~

### Set Custom Cache (In Seconds)

Example for 1 year:

~~~ .txt
GET `https://favicons.marsble.com/marsble.com?cache=31556952`
~~~

Installation
------------

Clone this repository to your server and serve it with your favorite web server.

Documentations
--------------

Documentation is available on [Developers page](https://developers.marsble.com/favicons).

License
-------

MIT
