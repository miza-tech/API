# BackendUser

CMS用户有关的API接口，包括：

| 功能 | API |
| ------ | ------ |
| 获取用户配置信息 | /cms/api/v1/config |
| 获取登录用户基本信息 | /cms/api/v1/profile |
| 更改登陆用户基本信息 | /cms/api/v1/profile |
| 新建用户 | /cms/api/v1/cms/users |
| 用户列表 | /cms/api/v1/cms/users |
| 编辑用户 | /cms/api/v1/cms/users/{id} |
| 用户密码重置 | /cms/api/v1/cms/users/{id}/passwordReset |
| 删除用户 | /cms/api/v1/cms/users/{id} |

## . 获取用户配置信息

* **URL:** /cms/api/v1/config
* **Description:** 当前用户的所有配置信息：根据自己权限所能看到的菜单、自己的权限列表、profile信息等
* **Method:** GET
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL


## . 获取登录用户基本信息

* **URL:** /cms/api/v1/profile
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改用户基本信息

* **URL:** /cms/api/v1/profile
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| age | Long | No | 年龄 |
	| gender | String | No | 性别, male\|female |


## . 新建用户

* **URL:** /cms/api/v1/cms/users
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| phone | String | Yes | 手机号码 |
	| realname | String | Yes | 真实姓名 |
	| idCard | String | Yes | 身份证号码 |
	| password | String | Yes | 初始化用户登录密码 |
	| roles | Array | Yes | 角色Id, [ 1, 2, 3, 4 ] |
	| department_id | Long | Yes | 所属部门ID |
	| password_reset_needed | Bool | Yes | 用户第一次登录时，是否需要重置密码 |
	| age | Long | No | 年龄 |
	| gender | String | No | 性别, male\|female |


## . 用户列表

* **URL:** /cms/api/v1/cms/users
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL

	| Parameter | Format | Require | Description |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 编辑用户

* **URL:** /cms/api/v1/cms/users/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| phone | String | Yes | 手机号码 |
	| realname | String | Yes | 真实姓名 |
	| idCard | String | Yes | 身份证号码 |
	| roles | Array | Yes | 角色Id, [ 1, 2, 3, 4 ] |
	| department_id | Long | Yes | 所属部门ID |
	| age | Long | No | 年龄 |
	| gender | String | No | 性别, male\|female |


## . 用户密码重置

* **URL:** /cms/api/v1/cms/users/{id}/passwordReset
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

* **URL:** /cms/api/v1/cms/users/{id}
* **Description:**
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL