# yaf-admin
使用yaf开发的后台骨架，带用户验证和rbac权限控制(a rbac admin skeleton power by yaf)
![1](/readme/1.png)
![1](/readme/2.png)

##主要功能
1. 后台加入布局插件
2. rbac权限控制
3. 封装数据库访问组件
4. 依赖注入服务
5. phpConsole调试开发插件
6. 整合phpunit单元测试
7. 整合phpdocument文档生成
8. 整合composer

##Requirements
- php5.3+

##测试环境部署
1. 导入**data/test/rbac相关表.sql**到数据库
2. 修改**application/conf/application.ini**的数据库配置
3. yaf扩展安装参考官方文档
