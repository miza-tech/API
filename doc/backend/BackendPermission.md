# BackendPermission

后台用户权限：

| 功能 | API |
| ------ | ------ |
| 权限列表 | /backend/api/v1/backend/permissons |


## . 权限列表

* **URL:** /backend/api/v1/backend/permissons
* **Description:**
* **Method:** GET
* **Request Parameter:**

	| Parameter | Format | Require | Description |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL