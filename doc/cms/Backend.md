# Backend

后台账号管理：

| 功能 | API |
| ------ | ------ |
| 新建后台账户 | /cms/api/v1/backends |
| 后台账户列表 | /cms/api/v1/backends |
| 更改后台账户 | /cms/api/v1/backends/{id} |
| 删除后台账户 | /cms/api/v1/backends/{id} |


## . 新建后台账户

* **URL:** /cms/api/v1/backends
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| username | String | Yes | 后台账户唯一名，根据该名词可以登录 |
	| display_name | String | Yes | 显示名称 |
	| password | String | Yes | 登录密码 |
	| password_reset_needed | Bool | Yes | 用户第一次登录时，是否需要重置密码 |


## . 后台账户列表

* **URL:** /cms/api/v1/backends
* **Description:**
* **Method:** GET
* **Request Parameter:**

	| Parameter | Format | Require | Description |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改后台账户

* **URL:** /cms/api/v1/backends/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| display_name | String | Yes | 显示名称 |


## . 后台账户密码重置

* **URL:** /cms/api/v1/backends/{id}/passwordReset
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| password | String | Yes | 初始化用户登录密码 |
	| password_reset_needed | Bool | Yes | 用户第一次登录时，是否需要重置密码 |


## . 删除权限

* **URL:** /cms/api/v1/backends/{id}
* **Description:**
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL