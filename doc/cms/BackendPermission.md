# BackendPermission

后台用户权限：

| 功能 | API |
| ------ | ------ |
| 新建权限 | /cms/api/v1/backend/permissons |
| 权限列表 | /cms/api/v1/backend/permissons |
| 更改权限 | /cms/api/v1/backend/permissons/{id} |
| 删除权限 | /cms/api/v1/backend/permissons/{id} |


## . 新建权限

* **URL:** /cms/api/v1/backend/permissons
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| name | String | Yes | 唯一名，这里的name直接对应api的名称，如 delete.entrust/roles/{role} |
	| display_name | String | Yes | 显示名称 |
	| action | String | Yes | 该操作对应的Controller及action，如 EntrustController@roleDestroy |
	| category_id | Long | No | 所对应的分类 |
	| middleware | Array | No | 该权限所需要的中间件 |
	| description | String | No | 描述 |


## . 权限列表

* **URL:** /cms/api/v1/backend/permissons
* **Description:**
* **Method:** GET
* **Request Parameter:**

	| Parameter | Format | Require | Description |
	| page | Long | No | 当前页码，页码默认从1开始 |
	| size | Long | No | 每页显示数据量，默认 size=20 |
	| {search field} | String | No | 搜索条件 |

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改权限

* **URL:** /cms/api/v1/backend/permissons/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| display_name | String | Yes | 显示名称 |
	| action | String | Yes | 该操作对应的Controller及action，如 EntrustController@roleDestroy |
	| middleware | Array | No | 该权限所需要的中间件 |
	| description | String | No | 描述 |
	| category_id | Long | No | 所对应的分类 |


## . 删除权限

* **URL:** /cms/api/v1/backend/permissons/{id}
* **Description:** 删除前会判断是否有角色或菜单关联权限，如果使用，则返回错误
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL