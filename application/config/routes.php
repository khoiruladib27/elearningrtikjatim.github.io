<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller']        = 'beranda';
$route['admin']                     = 'admin/dashboard';
$route['member']                    = 'member/dashboard';
$route['belajar/(:any)']            = 'member/belajar/index/$1';
$route['belajar/(:any)/(:any)']            = 'member/belajar/index/$1/$2';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
