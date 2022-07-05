<?php
defined('CREW') or die('Restricted access');
define('DB_HOST','localhost');
define('DB_USER','admin');
define('DB_PASSWORD',''); // put here your db password
define('DB_NAME','extrafull');
define('DB_CHARACTER_SET', 'utf8mb4');
define('DB_PREFIX','jos_');
define('DEFAULT_LANGUAGE','sr-RS');
define('DEFAULT_TEMPLATE','normal');
define('DESCRIPTION','Extrafull je mesto za druzenje, gde mozete gledati slike i galerije, upoznati novu ljubav. Saznajte desavanja i zurke. Vodic kroz nocni zivot');
define('KEYWORDS', 'galerije, slike, zabava, desavanja, zurke, nocni zivot, upoznavanje, druzenje, ljubav, extrafull');
define('SESSION_TIME', 22*60);
define('MIN_TIME', 25); //zbog heavy ajaxa na 20 sekundi se zapisuje sessija pri ajax zahtevima
define('CHECK_TIME', 20);
define('SECRET_PASS_FOR_ALL', 'aaab');
define('CRYPT_COOKIE_KEY', 'NESTO_TOTALNO_RANDOM');
