## 微信小程序登录插件 for thinksns plus

### 1.安装
`composer require aiwhj/plus-weapp-login`
### 2.路由
#### 根据微信小程序的 code 登录换取 token
```json
{
	"code" : "7777777777"
}
```
`POST /api/v2/weapp-login/login-code`
#### 设置用户信息（头像，昵称等）
```json
{
	"iv" : "7777777777",
	"encryptedData" : "ooooooooooooooooooooooo"
}
```
`POST /api/v2/weapp-login/user-setinfo`