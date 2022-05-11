# Symfony Console Demo

This project is a demonstration of `symfony/console` features.


To run the project with Docker.

With `bash`:

```console
docker build -t myconsole . && docker run -it myconsole bash -l
```

Or with `fish`:

```console
docker build -t myconsole . && docker run -it myconsole fish -l
```

# Examples

## Progress Bar

## Interactive questions

## Secret Input

## Read stdin

"Make every program a filter" is the last precept of [the UNIX Philosophy by Mike Gancarz](https://en.wikipedia.org/wiki/Unix_philosophy#Mike_Gancarz:_The_UNIX_Philosophy).
A filter is a program that gets most of its data from `stdin` and writes its main results to `stdout`.

`STDIN` is an always open stream of input data that can be used inside commands. But this needs to be wrapped into 
the `StreamableInputInterface::getStream` for testing.

```php
$stdin = ($input instanceof StreamableInputInterface ? $input->getStream() : null) ?? STDIN;
```

See example [`HeadCommand`](src/HeadCommand.php).

## Lock

## Render table

## Symfony Style or Custom Style

## Using option resolver
