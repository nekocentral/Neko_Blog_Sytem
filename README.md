# Neko IT Blog System


## Software used
* [Slim Framework](https://github.com/slimphp/Slim) for rendering and routing.
* [Slim Twig View](https://github.com/slimphp/Twig-View), An slim plugin to support the Twig template engine.
* [PHP-Markdown](https://github.com/michelf/php-markdown) by [Michel Fortin](https://github.com/michelf) to render the markdown files.

## How it works
An post is written in markdown and saved in the following filename format: `year-month-day_postname.md`, and put in the posts folder.
Each markdown file gets read by the `find_posts` function and put on the front page and the function `get_posts` passes them to be rendered for the final post page.