<?php
/**
 * Created by PhpStorm.
 * User: mhaaz
 * Date: 11/24/2017
 * Time: 11:02 AM
 */
use Michelf\Markdown;

function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

/* General Blog Functions */

function get_post_names(){

    static $_cache = array();

    if(empty($_cache)){

        // Get the names of all the
        // posts (newest first):

        $_cache = array_reverse(glob('posts/*.md'));
    }

    return $_cache;
}

function get_posts($page = 1, $perpage = 0){

    if($perpage == 0){
        $perpage = 10;
    }

    $posts = get_post_names();

    // Extract a specific page with results
    $posts = array_slice($posts, ($page-1) * $perpage, $perpage);

    $tmp = array();

    // Create a new instance of the markdown parser
    $md = new Markdown();

    foreach($posts as $k=>$v){

        $post = new stdClass;

        // Extract the date
        $arr = explode('_', $v);
        $post->date = strtotime(str_replace('posts/','',$arr[0]));

        // The post URL
        $post->url = '/'.date('Y/m', $post->date).'/'.str_replace('.md','',$arr[1]);

        // Get the contents and convert it to HTML
        $content = $md->defaultTransform(file_get_contents($v));

        // Extract the title and body
        $arr = explode('</h1>', $content);
        $post->title = str_replace('<h1>','',$arr[0]);
        $post->body = $arr[1];

        $tmp[] = $post;
    }

    return $tmp;
}

function find_posts($page = 1, $perpage = 0){

    if($perpage == 0){
        $perpage = 10;
    }

    $posts = get_post_names();

    // Extract a specific page with results
    $posts = array_slice($posts, ($page-1) * $perpage, $perpage);

    $tmp = array();

    // Create a new instance of the markdown parser
    $md = new Markdown();

    foreach($posts as $k=>$v){

        $post = new stdClass;

        // Extract the date
        $arr = explode('_', $v);
        $post->date = strtotime(str_replace('posts/','',$arr[0]));

        // The post URL
        $post->url = '/'.date('Y/m', $post->date).'/'.str_replace('.md','',$arr[1]);

        // Get the contents and convert it to HTML
        $content = $md->defaultTransform(file_get_contents($v));

        // Extract the title and body
        $arr = explode('</h1>', $content);
        $post->title = str_replace('<h1>','',$arr[0]);
        $post->body = limit_text($arr[1],36);

        $tmp[] = $post;
    }

    return $tmp;
}

// Find post by year, month and name
function find_post($year, $month, $name){

    foreach(get_post_names() as $index => $v){
        if( strpos($v, "$year-$month") !== false && strpos($v, $name.'.md') !== false){

            // Use the get_posts method to return
            // a properly parsed object

            $arr = get_posts($index+1,1);
            return $arr[0];
        }
    }

    return false;
}

// Helper function to determine whether
// to show the pagination buttons
function has_pagination($page = 1){
    $total = count(get_post_names());

    return array(
        'prev'=> $page > 1,
        'next'=> $total > $page*6
    );
}


// Turn an array of posts into a JSON
function generate_json($posts){
    return json_encode($posts);
}
