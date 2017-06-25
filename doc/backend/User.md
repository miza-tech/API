# BackendUser

后台用户有关的API接口，包括：

| 功能 | API |
| ------ | ------ |
| 获取用户配置信息 | /backend/api/v1/config |
| 获取登录用户基本信息 | /backend/api/v1/profile |
| 更改登陆用户基本信息 | /backend/api/v1/profile |
| 新建管理用户 | /backend/api/v1/backend/users |
| 管理用户列表 | /backend/api/v1/backend/users |
| 编辑管理用户 | /backend/api/v1/backend/users/{id} |
| 管理用户密码重置 | /backend/api/v1/backend/users/{id}/passwordReset |
| 删除用户 | /backend/api/v1/backend/users/{id} |


## . 获取用户配置信息

* **URL:** /backend/api/v1/config
* **Description:** 当前用户的所有配置信息：根据自己权限所能看到的菜单、自己的权限列表、profile信息等
* **Method:** GET
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL


## . 获取登录用户基本信息

* **URL:** /backend/api/v1/profile
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改登陆用户基本信息

* **URL:** /backend/api/v1/profile
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| age | Long | No | 年龄 |
	| gender | String | No | 性别, male\|female |
	| realname |  String | No | 真实姓名 |


## . 新建管理用户

* **URL:** /backend/api/v1/backend/users
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| username | String | Yes | 手机号码 |
	| realname | String | Yes | 真实姓名 |
	| idCard | String | Yes | 身份证号码 |
	| password | String | Yes | 初始化用户登录密码 |
	| roles | Array | Yes | 角色Id, [ 1, 2, 3, 4 ] |
	| department_id | Long | Yes | 所属部门ID |
	| password_reset_needed | Bool | Yes | 用户第一次登录时，是否需要重置密码 |
	| age | Long | No | 年龄 |
	| gender | String | No | 性别, male\|female |


## . 管理用户列表

* **URL:** /backend/api/v1/backend/users
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL

	| Parameter | Format | Require | Description |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 编辑管理用户

* **URL:** /backend/api/v1/backend/users/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| username | String | Yes | 手机号码 |
	| realname | String | Yes | 真实姓名 |
	| idCard | String | Yes | 身份证号码 |
	| roles | Array | Yes | 角色Id, [ 1, 2, 3, 4 ] |
	| department_id | Long | Yes | 所属部门ID |
	| age | Long | No | 年龄 |
	| gender | String | No | 性别, male\|female |


## . 管理用户密码重置

* **URL:** /backend/api/v1/backend/users/{id}/passwordReset
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| password | String | Yes | 初始化用户登录密码 |
	| password_reset_needed | Bool | Yes | 用户第一次登录时，是否需要重置密码 |


## . 删除用户

* **URL:** /backend/api/v1/backend/users/{id}
* **Description:**
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL
