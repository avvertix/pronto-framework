[![Build Status](https://travis-ci.org/avvertix/pronto-framework.svg?branch=master)](https://travis-ci.org/avvertix/pronto-framework)

# Pronto CMS Framework


> **warning** This is a work in progress and uses the development version of [Laravel Lumen](http://lumen.laravel.com/). If you're not comfortable with dev things, please wait until the Lumen 5.2 version will be out for everybody.


This is the base framwork for the flat-file based [Pronto CMS](https://github.com/avvertix/pronto-cms). This little framework provides Markdown parsing and Content extraction capabilities in Laravel service providers.

## Requirements

- php 5.5.9+
- php fileinfo extension
- Composer (for managing PHP dependencies)

## API and Services offered 

*to be described*

**pageview helper**

render a markdown file in a view and returns the composed view


**pageroute helper**

get a link to the specified markdown file


**content_path helper**

**image_path helper**

**assets_path helper**







## What content is understood

The content parser offered in this little framework is capable of understanding markdown files with/with-out front-matter.

Markdown files are forced to have `.md` extension and with UTF-8 character encoding.

If the front matter is set it must start with `---` and end with `---` on their respective lines, like

```
---
Order: 0
PageTitle: Welcome to Pronto
TOCTitle: Welcome
MetaDescription: This is Pronto, the CMS almost "ready".
MetaTags: pronto, cms
---

This is the page **static text**
```  

### Metadata

Actually some metadata contained in the front-matter section of the Markdown files are used inside the framework:

- **Order** - This is the order that is used in the left rail TOC, the page is left out of the TOC if this is blank
- **PageTitle** - The title used in the HTML title for the page and in search results
- **TOCTitle** (optional) - The title used in the left rail Table of Contents for this page. Use this is the title needs to be different than PageTitle
- **MetaDescription** - The meta description for this page which helps for search
- **MetaTags** - Further tags for this page again for search

