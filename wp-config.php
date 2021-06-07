<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'movies_db' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'root' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ')XrQ&FvgR)@y$:I*{Qv3p3D/b>Um&d/yu8v#+<J*&o-PdFLoaB]V)0zr;dcw8ijV' );
define( 'SECURE_AUTH_KEY',  '.Z;Z2a:-1jXVZ-]gB!%^z<tiILEUerNE6eqgU9Qzo*YZ*Y]`N7o@K0@=+FNEtqax' );
define( 'LOGGED_IN_KEY',    'DwW+|Z%C]w6ZO m}Kr~-Y~{{Wus<mhT(SEQk&+Dl!n@*g>G3B34v)>5}Z}`JOI(#' );
define( 'NONCE_KEY',        '4WY(L6zbW0>wok.v07V?/6q~)3yOR$S[V:M!X{b7jY7{:X@7i5<_A4}Z?+=X~AzK' );
define( 'AUTH_SALT',        'd@@WzX[oF~yjp.5Vv[zPj9s.[UKF%mBHm3lH|Orvr~]lc>q5K ?q})Yvaq/HWss%' );
define( 'SECURE_AUTH_SALT', '*hEeC8??JTUmFHatFw~&xmG6l<Kp<n~tTmgm%D,-K*RX5jBZ/,,:58A]tl5I[j#Z' );
define( 'LOGGED_IN_SALT',   'xnXs@(LK<FYnk}BZl(<4ErF@m%Q{EuOXEs>Rq,`I8P+(}!h04FWa?ABcsJmX[rZI' );
define( 'NONCE_SALT',       'f!783lD-D0x;y~ks3toI!5_{]stFMTWpp1i=p6WQQJf};[4eI^. r5~L;1J6^4+1' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
