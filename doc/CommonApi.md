# 用户登录、注册、获取图形验证码、短信验证码

| 功能 | API |
| ------ | ------ |
| 获取图形验证码图片地址 | /{cms\|backend\|client}/api/v1/captcha/img |
| 向用户发送短信 | /{cms\|backend\|client}/api/v1/captcha/sms |
| 密码登录 | /{cms\|backend\|client}/api/v1/auth/password |
| 短信登录 | /{cms\|backend\|client}/api/v1/auth/sms |
| 退出登录 | /{cms\|backend\|client}/api/v1/auth/logout |
| 短信验证码更改密码 | /{cms\|backend\|client}/api/v1/changePassword/sms |
| 旧密码更改密码 | /{cms\|backend\|client}/api/v1/changePassword/password |


## . 获取图形验证码图片地址

* **URL:** /{cms\|backend\|client}/api/v1/captcha/img
* **Description:**
* **Method:** GET
* **Request Parameter:** NULL
* **Login Required:** False
* **Request Body Parameter:** NULL


## . 向用户发送短信

* **URL:** /{cms\|backend\|client}/api/v1/captcha/sms
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** False
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| phone | String | Yes | 手机号码 |
	| captcha_img | Long | Yes | 图形验证码 |


## . 密码登陆

* **URL:** /{cms\|backend\|client}/api/v1/login/password
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** False
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| username | String | Yes | 手机号码或者用户名 |
	| password | String | Yes | 密码 |
	| captcha_img | Long | Yes | 图形验证码 |
	| remember_me | Bool | No | 是否记住登录 |


## . 短信登陆

* **URL:** /{cms\|backend\|client}/api/v1/login/sms
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** False
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| username | String | Yes | 手机号码 |
	| captcha_sms | Long | Yes | 短信验证码 |
	| captcha_img | Long | Yes | 图形验证码 |
	| remember_me | Bool | No | 是否记住登录 |


## . 短信验证码更改密码

* **URL:** /{cms\|backend\|client}/api/v1/changePassword/sms
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** False
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| username | String | Yes | 手机号码 |
	| captcha_sms | Long | Yes | 短信验证码 |
	| captcha_img | Long | Yes | 图形验证码 |
	| password | Long | Yes | 密码 |


## . 旧密码更改密码

* **URL:** /{cms\|backend\|client}/api/v1/changePassword/password
* **Description:**
* **Method:** POST
* **Request Parameter:** NULL
* **Login Required:** True
* **Request Body Parameter:**

	| Parameter | Format | Require | Description |
	| ------ | ------ | ------ | ------ |
	| old_password | String | Yes | 旧密码 |
	| new_password | String | Yes | 新密码 |
	| captcha_img | String | Yes | 图形验证码 |