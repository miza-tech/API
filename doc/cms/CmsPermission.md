# BackendPermission

CMS用户权限：

| 功能 | API |
| ------ | ------ |
| 新建角色 | /cms/api/v1/cms/permissons |
| 角色列表 | /cms/api/v1/cms/permissons |
| 更改角色 | /cms/api/v1/cms/permissons/{id} |
| 删除角色 | /cms/api/v1/cms/permissons/{id} |


## . 新建权限

* **URL:** /cms/api/v1/cms/permissons
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
	| menu_id | Long | No | 所对应的分类 |
	| middleware | Array | No | 该权限所需要的中间件 |
	| description | String | No | 描述 |


## . 权限列表

* **URL:** /cms/api/v1/cms/permissons
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改权限

* **URL:** /cms/api/v1/cms/permissons/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| name | String | No | code |
	| display_name | String | No | 显示名称 |
	| action | String | Yes | 该操作对应的Controller及action，如 EntrustController@roleDestroy |
	| middleware | Array | No | 该权限所需要的中间件 |
	| description | String | No | 描述 |
	| category_id | Long | No | 所对应的分类 |


## . 删除权限

* **URL:** /cms/api/v1/cms/permissons/{id}
* **Description:** 删除前会判断是否有角色或菜单关联权限，如果使用，则返回错误
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL