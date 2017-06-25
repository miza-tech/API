# CmsRole

CMS用户角色：

| 功能 | API |
| ------ | ------ |
| 新建角色 | /cms/api/v1/cms/roles |
| 角色列表 | /cms/api/v1/cms/roles |
| 更改角色 | /cms/api/v1/cms/roles/{id} |
| 删除角色 | /cms/api/v1/cms/roles/{id} |


## . 新建角色

* **URL:** /cms/api/v1/cms/roles
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| name | String | Yes | 角色唯一名 |
	| display_name | String | Yes | 角色显示名称 |
	| permissions | Array | Yes | 角色可以操作的权限, [1,3,5,6,9] |
	| description | String | No | 角色描述 |


## . 角色列表

* **URL:** /cms/api/v1/cms/roles
* **Description:**
* **Method:** GET
* **Request Parameter:**

	| Parameter | Format | Require | Description |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改角色

* **URL:** /cms/api/v1/cms/roles/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| display_name | String | Yes | 角色显示名称 |
	| permissions | Array | Yes | 角色可以操作的权限, [1,3,5,6,9] |
	| description | String | No | 角色描述 |


## . 删除角色

* **URL:** /cms/api/v1/cms/roles/{id}
* **Description:** 删除前会判断是否有用户使用该角色，如果使用，则返回错误
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL