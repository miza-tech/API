# CmsPermissionCategory

CMS权限分类：

| 功能 | API |
| ------ | ------ |
| 新建分类 | /cms/api/v1/cms/permission/categories |
| 分类列表 | /cms/api/v1/cms/permission/Categories |
| 更改分类 | /cms/api/v1/cms/permission/Categories/{id} |
| 删除分类 | /cms/api/v1/cms/permission/Categories/{id} |


## . 新建分类

* **URL:** /cms/api/v1/cms/permission/categories
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| display_name | String | Yes | 角色显示名称 |


## . 分类列表

* **URL:** /cms/api/v1/cms/permission/categories
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL

* **Login Required:** True
* **Request Body Parameter:** NULL


## . 更改分类

* **URL:** /cms/api/v1/cms/permission/categories/{id}
* **Description:**
* **Method:** PATCH
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| display_name | String | Yes | 角色显示名称 |


## . 删除分类

* **URL:** /cms/api/v1/cms/permission/categories/{id}
* **Description:** 删除前会判断是否有权限使用该分类，如果使用，则返回错误
* **Method:** DELETE
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:** NULL