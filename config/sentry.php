<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/24/2018
 * Time: 3:16 PM
 */
return array(
    'dsn' => 'https://f42bbf89aa83497bbbe3a22d9876936d:4b0da04299974afd89435f3b92c6fdfb@sentry.io/1195255',

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => true,
);
