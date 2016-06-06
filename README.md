柠檬图片上传搭建: http://www.jianshu.com/p/589e4d27a2c7

本系统是作为上传图片的服务端来使用, 本系统包含的功能有
- 支持外部目录部署作为存储图片的目录
- 支持生成图片缩略图和特效图
- 基于 Laravel 5.1 框架
- 图片去除重复, 重复图片不重复占用硬盘资源(md5方式)
- 图片默认压缩至 1440 宽度

## 搭建方式
### 克隆代码
代码地址
```
https://github.com/imvkmark/lemon_upload
```
### 导入数据库(没有使用 migrate)
数据库文件存放在 `resources/db/db.sql` 文件夹下, 导入到数据库中
配置数据库:
```
DB_HOST=localhost
DB_DATABASE=dbname
DB_USERNAME=dbuser
DB_PASSWORD=dbpwd
```
### 生成前端js 文件
生成　`requirejs` 加载的 `global.js` 文件
```
php artisan lemon:fe
```
### 访问后台界面
`/dsk_cp`

### 设置key 和secret

### 客户端安装和配置
安装 l5-upload-client 插件:
插件地址:  https://github.com/imvkmark/l5-upload-client
签名的生成规则见源码

## 加载流程

服务器请求　token_url 获取服务器上传需要的

## 请求参数
### 获取 token 信息
http://localhost/upload_token
- timestamp   : 请求时间戳
- app_key     : 服务器提供的 key
- version     : 默认 1.0
- sign        : 生成的签名

### 上传图片
http://localhost/upload_image
- image_file   : 上传的图片的字段名称
- field        : 默认是 image_file 如果有其他名称, 则这里需要传递其他名称的信息
- return_url   : 回调的url地址, 系统上传成功/失败后会传递 upload_return 到这个地址, 用于解决跨域问题

### 返回信息
#### 成功
```
{
	"status": "success",
	"msg": "图片上传成功",
	"success": true,
	"url": "http://www.lar_upload.com/thumber/config/201606/06/17/2303cetw1GNh.png",
	"destination": "201606/06/17/2303cetw1GNh.png"
}
```
#### 失败
```
{
	"status": "error",
	"msg": "图片过大, 上传失败"
}
```