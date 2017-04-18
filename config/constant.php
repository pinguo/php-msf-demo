<?php
/**
 * 业务常量的定义
 *
 * @author: xudianyang
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */
/**
 * 通用系统正常运行状态码
 */
define('SYS_OK', 200);
/**
 * 通用系统正常运行状态描述
 */
define('SYS_OK_STR', 'success');
/**
 * 通用系统级未找到页面错误码
 */
define('SYS_NOT_FOUND_PAGE', 400);
/**
 * 通用系统级未找到页面错误说明
 */
define('SYS_NOT_FOUND_PAGE_STR', 'Page Not Found');
/**
 * 通用系统框架级错误错误码
 */
define('SYS_MSF_ERROR', 500);
/**
 * 通用系统框架级错误错误说明
 */
define('SYS_MSF_ERROR_STR', '系统框架内部出错');
/**
 * 通用系统PHP内部错误错误码
 */
define('SYS_PHP_ERROR', 600);
/**
 * 通用系统PHP内部错误错误说明
 */
define('SYS_PHP_ERROR_STR', '系统应用程序出错');
/**
 * 通用系统级第三方服务错误错误码
 */
define('SYS_THIRD_PART_ERROR', 900);
/**
 * 通用系统级第三方服务错误说明
 */
define('SYS_THIRD_PART_ERROR_STR', '第三方服务出错');
/**
 * API模块错误码-业务异常通用状态
 */
define('SYS_API_ERRNO', 1000);

return [];