# BackendMenu

后台菜单管理：

| 功能 | API |
| ------ | ------ |
| 新建菜单 | /cms/api/v1/backend/menus |
| 角色菜单 | /cms/api/v1/backend/menus |
| 更改菜单 | /cms/api/v1/backend/menus/{id} |
| 删除菜单 | /cms/api/v1/backend/menus/{id} |


## . 新建菜单

* **URL:** /cms/api/v1/backend/menus
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| name | String | No | 名称，唯一标识符 |
	| display_name | String | Yes | 菜单显示名称 |
	| parent_id | Ling | No | 父级菜单Id |
	| url | String | No | 链接，当 `parent_id=null` 时候，`url`可以为空 |
	| permissions | Array | Yes | 菜单可以操作的权限, [1,3,5,6,9] |
	| description | String | No | 描述 |
	| icon | String | No | 图标 |


## . 菜单列表

* **URL:** /cms/api/v1/backend/menus
* **Description:**
* **Method:** GET
* **Request Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改菜单

* **URL:** /cms/api/v1/backend/menus/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| name | String | No | 名称，唯一标识符 |
	| display_name | String | Yes | 显示名称 |
	| parent_id | Ling | No | 父级菜单Id |
	| permissions | Array | Yes | 菜单可以操作的权限, [1,3,5,6,9] |
	| description | String | No | 描述 |
	| icon | String | No | 图标 |
	| move | String | No | 菜单上下移动位置, `move={up|down}` |


## . 删除菜单

* **URL:** /cms/api/v1/backend/menus/{id}
* **Description:** 删除前会判断是否有用户使用该角色，如果使用，则返回错误
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL