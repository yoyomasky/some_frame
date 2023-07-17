<?php
/**
 * @author: somewhere
 * @since: 2021/6/24
 * @version: 1.0
 */

$message = 'hello';

// 没有 "use"
$example = function () {
    var_dump($message);
};
$example();

// 继承 $message
$example = function () use ($message) {
    var_dump($message);
};
$example();

// Inherited variable's value is from when the function
// is defined, not when called
$message = 'world';
$example();
//
// Reset message
$message = 'hello222';
//
// Inherit by-reference
$example = function () use (&$message) {
    var_dump($message);
};
$example();

// The changed value in the parent scope
// is reflected inside the function call
$message = 'world';
$example();
//
//// Closures can also accept regular arguments
$example = function ($arg) use ($message) {
    var_dump($arg . ' ' . $message);
};
$example("hello");