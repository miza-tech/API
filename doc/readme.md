# 记录


## 1. 总体架构

`API server` 与 `client`的架构方式。主要考虑到`server`与`client`的完全解耦及将API的开放。`API server`采用	`oauth2.0`、`JWT`(Json Wen Token)认证方式。client端需要使用API的话，需要申请秘钥，拿到的秘钥对应相应的API权限与访问量。


## 2. `API server` 技术选型

API server选用 `laravel5.4`作为基础框架，laravel优秀在于它丰富的功能及插件，基本不需要重新造轮子就能使用，而且性能非常优秀。Laravel 有个轻量的框架`Lumen`，专门应用于API这种场景。但本项目暂时不选取Lumen作为API server的框架，原因在于laravel的功能更加完善，在研发初期有太多的不可预见性，有可能轻量级的lumen有些功能满足不了需求；另一个原因就是，迁移到lumen的成本非常的低，基本上不需要更改核心代码。因此，开始的时候可以选择laravel作为开发框架更为妥当。

server端所提供的API是支持跨域的，为的就是让client端可以很方便的API。

API 基本结构

```
/{backend|cms|client}/api/{version}/{category}/{operation}
```

## 3. `client` 技术选择

client有几种可能性，基于web的admin系统及APP。admin使用 `angularjs` + `adminlte`作为基础框架。APP使用 `ionic2` 作为基础框架。