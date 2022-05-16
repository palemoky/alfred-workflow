## 介绍

本仓库为个人开发的 Alfred Workflow，包括以下功能：

- [会议](https://github.com/palemoky/alfred-workflow/tree/master/meeting)
    - Zoom / Umeet
        - [x] 创建会议
        - [x] 通过 ID 入会
        - [x] 通过链接入会
    - 飞书（Lark）
        - [x] 通过 ID 入会
        - [x] 通过链接入会
        - [ ] 创建会议（未知创建会议的 Schema）
    - 钉钉（Dingtalk）
        - [ ] 通过链接入会（非规范化链接，已知 URL Schema 为`dingtalk://dingtalkclient/action/join_conf?roomCode={query}`，会议链接为`https://meeting.dingtalk.com/app?roomCode=123456`，通过会议链接无法直接进入会议）
        - [ ] 创建会议（未知创建会议的 Schema）
    - 腾讯会议（Tencent Meeting）
        - [ ] 由于分享链接中没有会议号，同时无法获取 URL Schema，无法完成任何功能
- [IP 查询](https://github.com/palemoky/alfred-workflow/tree/master/ip-tools)
    - [x] 查询本机 IP 信息，包括内网 IP、公网 IP
    - [x] 查询指定网卡信息
    - [x] 查询指定 IP 信息
- [时间戳工具](https://github.com/palemoky/alfred-workflow/tree/master/timestamp)
    - [x] 时间戳转日期
    - [x] 日期转时间戳

## Alfred Workflow 推荐

- [GitHub](https://github.com/gharlan/alfred-github-workflow) - GitHub Workflow for Alfred 4
- [Gitlab](https://github.com/lukewaite/alfred-gitlab) - A GitLab workflow for Alfred 3
- [Hash](https://github.com/X1A0CA1/alfred4-hash) - Hash calculator with MD5, SHA1, Base64, htpasswd, whirlpool and
  crc32 support
- [Jetbrains](https://github.com/bchatard/alfred-jetbrains) - Alfred3 workflow to easily open your projects with your
  favorite JetBrains product.
- [HTTP Status Codes](https://github.com/UpSync-Dev/alfred-http-status-codes) - Alfred Workflow to search for http
  status code meanings
- [Youdao Translate](https://github.com/wensonsmith/YoudaoTranslate) - Alfred Youdao Translate Workflow
- [emoji](https://github.com/carlosgaldino/alfred-emoji-workflow) - Alfred 2 workflow for searching emoji codes.

## Workflow 开发

开发前，先仔细阅读[官方文档](https://www.alfredapp.com/help/workflows/)，然后在 GitHub 中查找相应开发语言的库文件，如 PHP
的 [joetannenbaum/alfred-workflow](https://github.com/joetannenbaum/alfred-workflow)，Python
的 [NorthIsUp/alfred-workflow-py3](https://github.com/NorthIsUp/alfred-workflow-py3)。

### URL Schema

当我们通过链接打开本地应用时，都是通过 URL Schema 实现的，比如当我们在浏览器中打开 `https://zoom.us/j/123456` 时，在调试窗口的 Network
中可以看到，实际打开的是 `zoommtg://zoom.us/join?confno=123456`。要确定一个应用的 URL Schema 有两种方式：

1. 通过调试窗口的 Network [查看](https://raw.githubusercontent.com/palemoky/alfred-workflow/master/imgs/zoom_chrome_schemes.png)
2. 通过图示的方法[查看`Info.plist`文件](https://raw.githubusercontent.com/palemoky/alfred-workflow/master/imgs/app_url_schemes.png)

### Workflow变量

当 Workflow 中有一些个性化配置时，可以使用其变量功能，并注意是否勾选导出选项，避免敏感信息的泄露。各种开发语言读取 Workflow
变量方法查看[此文](https://www.deanishe.net/post/2018/10/workflow/environment-variables-in-alfred/)

### Intel 与 ARM 架构的差异

如果 Python、PHP 等在 ARM 架构下的 Mac 是通过 Homebrew 安装的，其路径在 `/opt/homebrew/bin` 下，需要注意路径变化导致的脚本无法执行问题

### 调试

开发过程中可开启 Workflow 界面的右上角调试按钮进行调试