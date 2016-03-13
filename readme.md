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








## Content

How to use Markdown to format your topic

The topics in this repository use Markdown. Here is a good overview of Markdown basics.

Topic Metadata

Topic metadata enables certain functionalities for the topics such as table of contents order, topic descriptions, and online search optimization as well as aiding Microsoft in evaluating the effectiveness of the content.

- **Order** - This is the order that is used in the left rail TOC, the page is left out of the TOC if this is blank
- **PageTitle** - The title used in the HTML title for the page and in search results
- **TOCTitle** (optional) - The title used in the left rail Table of Contents for this page. Use this is the title needs to be different than PageTitle
- **DateApproved** - This is set when the page is actually published on the portal. You can ignore it.
- **MetaDescription** - The meta description for this page which helps for search
- **MetaTags** - Further tags for this page again for search
