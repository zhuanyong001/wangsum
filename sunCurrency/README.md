# vite-vue3-template

## 项目拉取

```bash
# 克隆项目
git clone https://github.com/Ace627/vite-vue3-template.git -b ant-design-vue

# 进入项目目录
cd vite-vue3-template

# 安装依赖 | 建议不要直接使用 cnpm 安装依赖，会有各种诡异的 bug
pnpm install

# 启动服务
pnpm dev
```

## 目录参考

```bash
|-src
| |-components
|   |-Editor                          # 富文本编辑器组件
|   |-IFrame                          # 外链内嵌组件
|   |-QrCode                          # 二维码组件
| |-config
|   |-defaultSettings.ts              # 项目默认配置
|   |-permission.ts                   # 访问权限校验守卫
|   |-white-list.ts                   # 路由免授权白名单
| |-utils
    |-cache
|     |-local-storage.ts              # 统一处理 localStorage 的存储
|   |-request.ts                      # 对 axios 的二次封装
|   |-validate.ts                     # 常用的校验方法合集
| |-views
|   |-Login
|    |-index_v01.vue                  # DCEM 登录模板
|    |-index.vue                      # 若依管理系统登录页
```
