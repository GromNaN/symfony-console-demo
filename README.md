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

Progress Bar 

See example [`FarmingCommand`](src/Command/FarmingCommand.php)

## Interactive questions

Commands can be interactive: ask questions.

The best practice is to define all arguments and options, so that the command can be used without interaction, and
ask for all missing values in [`Command::interact`](https://symfony.com/doc/current/console.html#command-lifecycle).
This is the last place where you can ask for missing options/arguments before validation.

See example [`AskColorCommand`](src/Command/AskColorCommand.php)

Also, Symfony Demo's [`AddUserCommand`](https://github.com/symfony/demo/blob/main/src/Command/AddUserCommand.php)

## Secret Input

## Read stdin

"Make every program a filter" is the last precept of [the UNIX Philosophy by Mike Gancarz](https://en.wikipedia.org/wiki/Unix_philosophy#Mike_Gancarz:_The_UNIX_Philosophy).
A filter is a program that gets most of its data from `stdin` and writes its main results to `stdout`.

`STDIN` is an always open stream of input data that can be used inside commands. But this needs to be wrapped into 
the `StreamableInputInterface::getStream` for testing.

```php
$stdin = ($input instanceof StreamableInputInterface ? $input->getStream() : null) ?? STDIN;
```

See example [`HeadCommand`](src/Command/HeadCommand.php).

## Render table



## [Hyperlinks](https://symfony.com/blog/new-in-symfony-4-3-console-hyperlinks)

Rendering clickable hyperlinks is one of the most important missing features of Console apps and commands.
Although most of the terminal emulators auto-detect URLs and allow to click on them with some key combination, it's not
possible to render clickable text that points to some arbitrary URL.

See example [`ReleasesCommand`](src/Command/ReleasesCommand.php)

## Lock

## Symfony Style or Custom Style

## Using option resolver
