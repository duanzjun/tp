<?php
return array(
    'DB_TYPE' => 'mysqli',
    // 'DB_HOST' => '192.168.2.222',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'tp',
    'DB_USER' => 'root',
    'DB_PWD' => '123456',
    'DB_PORT' => 3306,
    'DB_PREFIX' => 'tp_',

    'URL_MODEL' => 0,
    'USER_AUTH_ON' => true, //是否需要认证
    'USER_AUTH_TYPE' => 1, //认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', //认证识别号
    'ADMIN_AUTH_KEY' => 'administrator',
    'REQUIRE_AUTH_MODULE' => '', // 需要认证模块
    'NOT_AUTH_MODULE' => 'Login,Public', //无需认证模块
    'USER_AUTH_GATEWAY' => '/Admin/Login', //认证网关
    'RBAC_DB_DSN' => '', // 数据库连接DSN
    'RBAC_ROLE_TABLE' => 'tp_role', //角色表名称
    'RBAC_USER_TABLE' => 'tp_role_user', //用户表名称
    'RBAC_ACCESS_TABLE' => 'tp_access', //权限表名称
    'RBAC_NODE_TABLE' => 'tp_node', //节点表名称
);