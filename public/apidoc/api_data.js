define({ "api": [
  {
    "type": "get",
    "url": "/api/getSession",
    "title": "获取学期信息",
    "group": "other",
    "success": {
      "examples": [
        {
          "title": "返回学期信息列表,",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  学期标识\n      \"year\": 2016  //数字型 学年\n      \"team\": 2  //  数字型 学期\n    }\n  ],\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./SessionController.php",
    "groupTitle": "other",
    "name": "GetApiGetsession"
  },
  {
    "type": "delete",
    "url": "/api/role/:id",
    "title": "删除指定的角色信息",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>角色标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "删除失败，没有指定的角色:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404，\n\"message\": \"删除失败\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./RoleController.php",
    "groupTitle": "role",
    "name": "DeleteApiRoleId"
  },
  {
    "type": "get",
    "url": "/api/role",
    "title": "显示学期列表",
    "group": "role",
    "success": {
      "examples": [
        {
          "title": "返回所有的角色",
          "content": "HTTP/1.1 200 OK\n {\n \"data\": [\n {\n \"id\": 2,\n \"name\": \"admin\",\n \"explain\": \"管理员\",\n \"remark\": null\n }\n ],\n \"status\": \"success\",\n \"status_code\": 200,\n \"links\": {\n \"first\": \"http://manger.test/api/role?page=1\",\n \"last\": \"http://manger.test/api/role?page=1\",\n \"prev\": null,\n \"next\": null\n },\n \"meta\": {\n \"current_page\": 1,\n \"from\": 1,\n  \"last_page\": 1,\n \"path\": \"http://manger.test/api/role\",\n \"per_page\": 15,\n \"to\": 30,\n \"total\": 5\n }\n }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./RoleController.php",
    "groupTitle": "role",
    "name": "GetApiRole"
  },
  {
    "type": "get",
    "url": "/api/role/:id",
    "title": "获取一条角色",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>角色标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"data\": [\n {\n \"id\": 2,\n \"name\": \"admin\",\n \"explain\": \"管理员\",\n \"remark\": null\n }\n],\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "指定的角色不存在:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./RoleController.php",
    "groupTitle": "role",
    "name": "GetApiRoleId"
  },
  {
    "type": "patch",
    "url": "/api/role/:id",
    "title": "更新角色信息",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>角色标识 路由上使用</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "explain",
            "description": "<p>角色描述</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建立学期 2017-2018上学期:",
          "content": "{\nname: 'admin',\nexplain: '管理员',\nremark: '管理员'\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./RoleController.php",
    "groupTitle": "role",
    "name": "PatchApiRoleId"
  },
  {
    "type": "post",
    "url": "/api/role",
    "title": "新建一条角色信息",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "explain",
            "description": "<p>角色说明</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>角色备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建",
          "content": "{\nname: 'app',\nexplain: '应用管理者'\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRole"
  },
  {
    "type": "delete",
    "url": "/api/session/:id",
    "title": "删除指定的学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>学期标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "删除失败，没有指定的学期:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404，\n  \"message\": \"删除失败\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./SessionController.php",
    "groupTitle": "session",
    "name": "DeleteApiSessionId"
  },
  {
    "type": "get",
    "url": "/api/session",
    "title": "显示学期列表",
    "group": "session",
    "success": {
      "examples": [
        {
          "title": "返回学期信息列表，分页显示，每页15条记录,",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  学期标识\n      \"year\": 2016  //数字型 学年\n      \"team\": 2  //  数字型 学期\n      \"remark\": \"2016-2017下学期\" // 备注说明\n      \"one\": 20,  // 高一年级班级数\n      \"two\": 20,  // 高二年级班级数\n      \"three\": 20  // 高三年级班级数\n    }\n  ],\n \"status\": \"success\",\n \"status_code\": 200,\n \"links\": {\n \"first\": \"http://manger.test/api/session?page=1\",\n \"last\": \"http://manger.test/api/session?page=1\",\n \"prev\": null,\n \"next\": null\n },\n \"meta\": {\n \"current_page\": 1,   //当前页\n \"from\": 1,  // 当前记录\n \"last_page\": 1,    //最后一页\n \"path\": \"http://manger.test/api/session\",\n \"per_page\": 15,  //\n \"to\": 4,  //当前页最后一条记录\n \"total\": 4 // 总记录\n }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./SessionController.php",
    "groupTitle": "session",
    "name": "GetApiSession"
  },
  {
    "type": "get",
    "url": "/api/session/:id",
    "title": "获取指定学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>学期标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  学期标识\n      \"year\": 2016  //数字型 学年\n      \"team\": 2  //  数字型 学期\n      \"remark\": \"2016-2017下学期\" // 备注说明\n      \"one\": 20,  // 高一年级班级数\n      \"two\": 20,  // 高二年级班级数\n      \"three\": 20  // 高三年级班级数\n    }\n  ],\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "指定的学期不能存在:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./SessionController.php",
    "groupTitle": "session",
    "name": "GetApiSessionId"
  },
  {
    "type": "patch",
    "url": "/api/session/:id",
    "title": "更新学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>学期标识 路由上使用</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "year",
            "description": "<p>学年</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "1",
              "2"
            ],
            "optional": false,
            "field": "team",
            "description": "<p>学期(1=&gt;上学期 2=&gt;下学期)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "one",
            "description": "<p>高一班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "two",
            "description": "<p>高二班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "three",
            "description": "<p>高三班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建立学期 2017-2018上学期:",
          "content": "  {\n     year: 2017,\n     team: 1,\n     remark: '2017-2018上学期',\n     one: 20,\n     two: 20,\n     three: 20\n\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"success\",\n  \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404,\n  \"message\": \"验证出错,请按要求填写\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./SessionController.php",
    "groupTitle": "session",
    "name": "PatchApiSessionId"
  },
  {
    "type": "post",
    "url": "/api/session",
    "title": "新建一个学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "year",
            "description": "<p>学年</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "1",
              "2"
            ],
            "optional": false,
            "field": "team",
            "description": "<p>学期(1=&gt;上学期 2=&gt;下学期)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "one",
            "description": "<p>高一班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "two",
            "description": "<p>高二班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "three",
            "description": "<p>高三班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建立学期 2017-2018上学期:",
          "content": "  {\n     year: 2017,\n     team: 1,\n     one: 20,\n     two: 20,\n     three: 20\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"success\",\n  \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404,\n  \"message\": \"验证出错,请按要求填写\"\n}",
          "type": "json"
        },
        {
          "title": "重复提交:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 400,\n  \"message\": \"你提交的学期信息已经存在，无法新建\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./SessionController.php",
    "groupTitle": "session",
    "name": "PostApiSession"
  },
  {
    "type": "get",
    "url": "/api/customers/customersList",
    "title": "4.返回已有客户名称",
    "group": "客户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 customers.customersList\n2、无需参数\n3、返回对应的ID和名称，ID可用于编辑的时候判断是否是当前记录进行校验",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CustomerController.php",
    "groupTitle": "客户管理",
    "name": "GetApiCustomersCustomerslist"
  },
  {
    "type": "get",
    "url": "/api/customers/{id}",
    "title": "2.获取指定客户信息",
    "group": "客户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 customer.getOne\n2、必须传递ID，正整数,\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CustomerController.php",
    "groupTitle": "客户管理",
    "name": "GetApiCustomersId"
  },
  {
    "type": "get",
    "url": "/api/customers/index",
    "title": "1.客户列表",
    "group": "客户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 customer.index\n2、可选参数\npageSize 分页数量，默认为15\nname 客户名称,默认为空,模糊匹配 like %name%",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "examples": [
        {
          "title": "数据库表结构",
          "content": "increments('id')->comment('客户编号');\nstring('name', 20)->unique()->comment('客户名称');\nstring('from', 20)->nullable()->comment('客户来源');\nstring('linkman',20)->nullable()->comment('联系人');\nstring('phone', 20)->nullable()->comment('联系电话');\nstring('email', 50)->nullable()->comment('电子邮件');\nstring('card_type',20)->nullable()->comment('证件类型');\nstring('card_number', 30)->nullable()->comment('证件号码');\nstring('fax', 20)->nullable()->comment('传真号码');\nstring('remark')->nullable()->comment('备注');",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CustomerController.php",
    "groupTitle": "客户管理",
    "name": "GetApiCustomersIndex"
  },
  {
    "type": "post",
    "url": "/api/customers/{id?}",
    "title": "3.新增或更新客户信息",
    "group": "客户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 customers.saveOne\n2、必选参数：\nID，客户编号，作为URL必填,大于0表示更新，否则新增\nname，客户名称，字符串不能为空\n3、可选参数参照客户列表中的数据库结构",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CustomerController.php",
    "groupTitle": "客户管理",
    "name": "PostApiCustomersId"
  },
  {
    "type": "delete",
    "url": "/api/admin/:id",
    "title": "8.删除指定的管理员",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "简要说明",
          "content": "HTTP/1.1 200 OK\n作为URL的ID参数必填，成功code为1，否则为0",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "DeleteApiAdminId"
  },
  {
    "type": "get",
    "url": "/api/admin",
    "title": "4.显示管理员列表",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、默认返回全部数据的分页显示\n2、可选参数：\nname 值不为空时表示 like方式过滤\nphone_number 值不为空时表示 like方式过滤\nemail 值不为空时表示 like方式过滤",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "GetApiAdmin"
  },
  {
    "type": "get",
    "url": "/api/admin/:id",
    "title": "6.显示指定的管理员",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "返回管理员信息",
          "content": "HTTP/1.1 200 OK\n{\n\"data\": {\n  \"id\": 1,\n  \"name\": \"wmhello\",\n  \"email\": \"871228582@qq.com\",\n  \"role\": \"admin\",\n  \"avatar\": \"\"\n},\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "GetApiAdminId"
  },
  {
    "type": "get",
    "url": "/api/user",
    "title": "91.获取当前登录的用户信息",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "信息获取成功",
          "content": "HTTP/1.1 200 OK\n{\n\"data\": {\n   \"id\": 1,\n   \"name\": \"xxx\",\n   \"email\": \"xxx@qq.com\",\n   \"roles\": \"xxx\", //角色: admin或者editor\n   \"avatar\": \"\"\n },\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "GetApiUser"
  },
  {
    "type": "post",
    "url": "/api/admin",
    "title": "5.建立新的管理员",
    "group": "用户管理",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登录名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登录密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password_confirmation",
            "description": "<p>用户登录密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "phone_number",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"男\"",
              "\"女\""
            ],
            "optional": true,
            "field": "sex",
            "defaultValue": "男",
            "description": "<p>性别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "state",
            "description": "<p>状态</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": true,
            "field": "birthday",
            "description": "<p>出生日期</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": true,
            "field": "work_time",
            "description": "<p>入职日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "card_type",
            "description": "<p>证件类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "card_number",
            "description": "<p>证件号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "duty",
            "description": "<p>职务</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "level",
            "description": "<p>等级</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "from",
            "description": "<p>来源</p>"
          },
          {
            "group": "Parameter",
            "type": "integer",
            "optional": true,
            "field": "fix_salary",
            "description": "<p>固定工资</p>"
          },
          {
            "group": "Parameter",
            "type": "integer",
            "optional": true,
            "field": "work_salary",
            "description": "<p>出班工资</p>"
          },
          {
            "group": "Parameter",
            "type": "integer",
            "optional": true,
            "field": "extra_salary",
            "description": "<p>加班工资</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "family_address",
            "description": "<p>家庭地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "personal_address",
            "description": "<p>现住址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "简要说明:",
          "content": "1、除了必选参数，其余的参数可以为空或者不传\n2、出生日期和入职日期必须为日期型\n3、工资必须为不小于0的整数\n4、role 必须要以数组形式传递 [\"editor\",\"admin\"]",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "PostApiAdmin"
  },
  {
    "type": "post",
    "url": "/api/admin/:id/reset",
    "title": "93.设置指定的管理员的密码",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、必填参数 password 用户新密码\n2、必填参数 password_confirmation 重复新密码\n3、作为URL部分的ID必填",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "PostApiAdminIdReset"
  },
  {
    "type": "post",
    "url": "/api/admin/modify",
    "title": "92.登录用户修改密码",
    "group": "用户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、必选参数\noldPassword  原来密码\npassword 新密码\npassword_confirmation 重复新密码\n2、只有登录后才能修改自己的密码，系统自动判断当前登录用户",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "PostApiAdminModify"
  },
  {
    "type": "post",
    "url": "/api/admin/uploadAvatar",
    "title": "9.头像图片上传",
    "group": "用户管理",
    "header": {
      "examples": [
        {
          "title": "http头部请求:",
          "content": "    {\n      \"content-type\": \"application/form-data\"\n    }\n返回情况请看postman调试结果",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "PostApiAdminUploadavatar"
  },
  {
    "type": "post",
    "url": "/api/login",
    "title": "1.用户登录",
    "group": "用户管理",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户email</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户密码</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "登录成功",
          "content": "HTTP/1.1 200 OK\n{\n\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJS\",\n\"expires_in\": 900 // 过期时间\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "用户身份验证失败",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"login error\",\n\"status_code\": 0,\n\"message\": \"用户名或者密码输入错误\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./Auth/LoginController.php",
    "groupTitle": "用户管理",
    "name": "PostApiLogin"
  },
  {
    "type": "post",
    "url": "/api/logout",
    "title": "3.注销用户登录",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "注销成功",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200,\n\"message\": \"logout success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./Auth/LoginController.php",
    "groupTitle": "用户管理",
    "name": "PostApiLogout"
  },
  {
    "type": "post",
    "url": "/api/token/refresh",
    "title": "2.刷新Token",
    "group": "用户管理",
    "success": {
      "examples": [
        {
          "title": "刷新成功",
          "content": "HTTP/1.1 200 OK\n{\n\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJS\",\n\"expires_in\": 900 // 过期时间\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "刷新失败",
          "content": "HTTP/1.1 401 未认证\n{\n\"status\": \"login error\",\n\"status_code\": 401,\n\"message\": \"Credentials not match\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./Auth/LoginController.php",
    "groupTitle": "用户管理",
    "name": "PostApiTokenRefresh"
  },
  {
    "type": "put",
    "url": "/api/admin/:id",
    "title": "7.更新指定的管理员",
    "group": "用户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明:",
          "content": "具体参数情况请参考创建用户的参数\n作为URL部分的ID必填",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "PutApiAdminId"
  },
  {
    "type": "get",
    "url": "/api/cars/carNumberList",
    "title": "4.返回已有车牌号列表",
    "group": "车辆管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 cars.carNumberList\n2、无需参数\n3、返回对应的ID和车牌号，ID可用于编辑的时候判断是否是当前记录进行校验",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CarController.php",
    "groupTitle": "车辆管理",
    "name": "GetApiCarsCarnumberlist"
  },
  {
    "type": "get",
    "url": "/api/cars/{id}",
    "title": "2.获取指定车俩信息",
    "group": "车辆管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 cars.getCar\n2、必须传递ID，正整数,",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "成功返回:",
          "content": "{\n\"code\": 1,\n\"info\": \"获取信息成功！\",\n\"data\": {\n\"id\": 1,\n\"car_type\": \"SUV\",\n\"car_number\": \"苏A88888\",\n\"state\": \"在位\",\n\"linkman\": \"张三\",\n\"phone\": \"12345678\",\n\"work_price\": 100,\n\"from\": \"员工\",\n\"remark\": \"备注\",\n\"created_at\": \"2018-06-11 23:40:24\",\n\"updated_at\": \"2018-06-11 23:40:25\"\n}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CarController.php",
    "groupTitle": "车辆管理",
    "name": "GetApiCarsId"
  },
  {
    "type": "get",
    "url": "/api/cars/index",
    "title": "1.车辆列表",
    "group": "车辆管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 cars.index\n2、可选参数\npageSize 分页数量，默认为15\ncar_type 车辆型号,默认为空,模糊匹配 like %car_type%\nstate    状态，默认为空,精确匹配 =state",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "examples": [
        {
          "title": "数据库表结构",
          "content": "integer('id')->comment('车辆编号');\n$string('car_type', 20)->nullable(false)->comment('车辆型号');\n$string('car_number', 20)->nullable(false)->unique()->comment('车牌号');\n$string('state',20)->comment('状态')->nullable();\n$string('linkman', 20)->nullable(false)->comment('联系人');\n$string('phone', 20)->nullable()->comment('联系电话');\n$integer('work_price')->default(0)->comment('租车价格');\n$string('from',20)->comment('来源')->nullable();\n$string('remark',100)->comment('备注')->nullable();",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CarController.php",
    "groupTitle": "车辆管理",
    "name": "GetApiCarsIndex"
  },
  {
    "type": "post",
    "url": "/api/cars/{id?}",
    "title": "3.新增或更新车俩信息",
    "group": "车辆管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 cars.saveCar\n3、必选参数：\nID，车辆编号，作为URL必填,大于0表示更新，否则新增\ncar_type，车辆型号，字符串不能为空\ncar_number，车牌号，字符串不能为空,数据库唯一\nlinkman，联系人，字符串不能为空\nwork_price，租车价格,整数，最小值0",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CarController.php",
    "groupTitle": "车辆管理",
    "name": "PostApiCarsId"
  }
] });
