# Rules

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/Rules/ci.yml?branch=master)](https://github.com/ProfessionalWiki/Rules/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/ProfessionalWiki/Rules/branch/master/graph/badge.svg)](https://codecov.io/gh/ProfessionalWiki/Rules)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/rules/v/stable)](https://packagist.org/packages/professional-wiki/rules)
[![Download count](https://poser.pugx.org/professional-wiki/rules/downloads)](https://packagist.org/packages/professional-wiki/rules)
[![License](https://poser.pugx.org/professional-wiki/rules/license)](LICENSE)

TODO

- [Introduction to the extension](https://professional.wiki/en/extension/rules#Overview)
- [Usage documentation](https://professional.wiki/en/extension/rules#Usage)
- [Installation](https://professional.wiki/en/extension/rules#Installation)
- [Configuration](https://professional.wiki/en/extension/rules#Configuration)
- [Development](#development)
- [Release notes](#release-notes)

Get professional support for this extension via [Professional Wiki], its creators and maintainers.
We provide [MediaWiki Development], [MediaWiki Hosting], and [MediaWiki Consulting] services.

## Development

Run `composer install` in `extensions/Rules/` to make the code quality tools available.

### Running Tests and CI Checks

You can use the `Makefile` by running make commands in the `Rules` directory.

Commands to run in a MediaWiki environment/container:

* `make` or `make ci`: Run everything
* `make test`: Run all PHP tests
* `make phpunit --filter FooBar`: run only PHPUnit tests with FooBar in their name
* `make cs`: Run PHP style checks and static analysis
* `make phpcs`: Run PHP style checks
* `make stan`: Run PHP static analysis
* `make stan-baseline`: Update the PHPStan baseline file (which contains errors we wish to ignore)

Commands that use Docker:

* `make lint` Lint JS, CSS, and i18n files
* `make js` Run all JS checks

## Release Notes

### Version 1.0.0

* WIP

[Professional Wiki]: https://professional.wiki
[MediaWiki Hosting]: https://pro.wiki
[MediaWiki Development]: https://professional.wiki/en/mediawiki-development
[MediaWiki Consulting]: https://professional.wiki/en/mediawiki-consulting-services
