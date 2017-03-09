---
title: Application
layout: documentation
---

# Application {#toc}

* TOC
{:toc}

## [Application](#application) {#application}

An application represents a single website, consisting of settings, pages and URL(s).

### [URLS](#urls) {#urls}

Each application URL specified in the `application_urls` table denotes a domain which can access your application and its content. Subdomains (including www.) need to specified as additional records.

### [Settings](#settings) {#settings}

You may also attach [Field]({{ site.baseurl }}{% link models/field.md %}) models to your application to supply application-specific settings (such as favicons, etc). The setting value can be accessed by referring to the `field_name` of the associated Field:

```php
$favicon = $application->getSetting('favicon');
```

The method returns null if the associated setting could not be found.

## [Matching your application to the current URL](#matching-application) {#matching-application}

The application is matched by the [RequestMatcher]({{ site.baseurl }}{% link components/request-matcher.md %}) class, searching the database for a URL that matches the current URI. If no matching URL is found, the Laravel application will abort with a 404 error.

### [Getting the current Application](#current-application) {#current-application}

If an application is matched to your current URI, the model for the application you are interacting with is accessible through the `Soda` facade.

```php
use Soda;

$currentApplication = Soda::getApplication();
```
