# PHP Generator

This POC presents the different use cases of PHP generators.

To quote the [PHP documentation](https://www.php.net/manual/en/language.generators.overview.php)
>Since (PHP 5 >= 5.5.0, PHP 7, PHP 8)  
>Generators provide an easy way to implement simple iterators without the overhead or complexity of implementing a class that implements the Iterator interface.
>
>A generator allows you to write code that uses foreach to iterate over a set of data without needing to build an array in memory, which may cause you to exceed a memory limit, or require a considerable amount of processing time to generate. Instead, you can write a generator function, which is the same as a normal function, except that instead of returning once, a generator can yield as many times as it needs to in order to provide the values to be iterated over.
>
>A simple example of this is to reimplement the range() function as a generator. The standard range() function has to generate an array with every value in it and return it, which can result in large arrays: for example, calling range(0, 1000000) will result in well over 100 MB of memory being used.
>
>As an alternative, we can implement an xrange() generator, which will only ever need enough memory to create an Iterator object and track the current state of the generator internally, which turns out to be less than 1 kilobyte.

## Yield keyword

### Range reimplement

An example to create a range with less memory used.
