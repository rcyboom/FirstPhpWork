define({ "api": [
  {
    "type": "get",
    "url": "/api/userpays/{id}",
    "title": "2.获取指定编号的奖惩信息",
    "group": "人员奖惩",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 userpays.getOne\n2、必须传递ID，正整数",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "examples": [
        {
          "title": "关于奖惩的其他说明",
          "content": "account_id为工资结算单的id，不允许修改的，为0显示为未结算，大于0显示为已结算",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserPayController.php",
    "groupTitle": "人员奖惩",
    "name": "GetApiUserpaysId"
  },
  {
    "type": "get",
    "url": "/api/userpays/index",
    "title": "1.人员奖惩列表",
    "group": "人员奖惩",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 userpays.index\n2、可选参数\npageSize 分页数量，默认为15\nobject_type 奖惩对象类型，范围：车辆、员工,默认为空表示全部\nobject_id  奖惩对象ID，人员或者车辆ID，默认为空表示全部,精确匹配\naccount_id  结算ID，默认为空表示全部,精确匹配，0表示未结算",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "examples": [
        {
          "title": "数据库表结构",
          "content": "id 奖惩编号\nobject_id 奖惩对象编号\nobject_type 奖惩对象类型，范围：车辆、员工\naccount_id 结算编号，也就是收支记录编号\ntime 奖惩时间\ntype 奖惩类型，奖励、惩罚、预支\nmoney 金额\nscore 奖惩评分\nreason 奖惩原因说明\ncreated_at 创建记录时间，服务器时间，不可更改",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserPayController.php",
    "groupTitle": "人员奖惩",
    "name": "GetApiUserpaysIndex"
  },
  {
    "type": "post",
    "url": "/api/userpays/delete",
    "title": "4.删除指定编号的奖惩信息",
    "group": "人员奖惩",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 userpays.delete\n2、必选参数\nid，正整数,需要删除的记录编号\n注意，此处id默认为0，如果没获取到id将返回错误消息：未找到对应的编号奖惩信息",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserPayController.php",
    "groupTitle": "人员奖惩",
    "name": "PostApiUserpaysDelete"
  },
  {
    "type": "post",
    "url": "/api/userpays/{id?}",
    "title": "3.新增或更新一条奖惩信息",
    "group": "人员奖惩",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 userpays.saveOne\n2、必选参数：\n id，奖惩编号，作为url必填,大于0表示更新，否则新增\n\tobject_id 正整数，人员或者车辆编号，且必须真实存在\n\tobject_type 可选项：员工、车辆\n\ttime 日期时间型，奖惩时间\n\ttype 字符串20，奖惩类型，可选项：奖励、惩罚、预支\n\tmoney 数字类型，两位小数，奖惩金额\n\tscore 数字类型，两位小数，奖惩评分\n\treason 字符串，奖惩原因",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserPayController.php",
    "groupTitle": "人员奖惩",
    "name": "PostApiUserpaysId"
  },
  {
    "type": "get",
    "url": "/api/tasks/FreeCars",
    "title": "93.获取在位空闲车辆列表",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.FreeCars\n2、无需参数，无需分页\n特殊说明：\n1、只返回state状态为在位的车辆\n2、排除出勤任务中end_time为空也就是未收工的车辆",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksFreecars"
  },
  {
    "type": "get",
    "url": "/api/tasks/FreeMans",
    "title": "92.获取在位空闲人员列表",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.FreeMans\n2、无需参数，无需分页\n特殊说明：\n1、只返回state状态为在位的人员\n2、排除出勤任务中end_time为空也就是未收工的人员",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksFreemans"
  },
  {
    "type": "get",
    "url": "/api/tasks/getOption",
    "title": "95.获取系统设置",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.getOption\n2、key_name 字符串，返回其对应的数组",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksGetoption"
  },
  {
    "type": "get",
    "url": "/api/tasks/getStateList",
    "title": "94.获取任务各种状态选项",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.getStateList\n2、无需参数，无需分页\n特殊说明：\n1、返回的 states 作为任务状态下拉列表的值，你需要增加一个全部选项",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksGetstatelist"
  },
  {
    "type": "get",
    "url": "/api/tasks/index",
    "title": "1.任务列表",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.index\n2、可选参数\npageSize 分页数量，默认为15\ncustomer_name 客户名称,默认为空,模糊匹配\ntitle    任务名称，默认为空,模糊匹配\nstate    任务状态  默认为全部，我给你一个API，这个API返回的选项作为下拉列表\nstart_time 开始时间  默认为当前时间前推一个月\nend_time   截至时间  默认为当前时间",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "examples": [
        {
          "title": "数据库表结构",
          "content": "increments('id')->comment('任务编号');\ndateTime('check_time')->nullable(false)->default(now())->comment('登记时间');\ninteger('customer_id')->nullable(false)->comment('客户编号');\nstring('title', 30)->nullable(false)->comment('任务名称');\nstring('type', 20)->default('默认类型')->comment('类型');\nstring('state', 20)->default('已登记')->comment('状态');\nstring('linkman',20)->nullable()->comment('联系人');\nstring('phone', 20)->nullable()->comment('联系电话');\nstring('station', 60)->nullable()->comment('工作地点');\ndateTime('start_time')->nullable()->default(now())->comment('开始时间');\ndateTime('end_time')->nullable()->comment('结束时间');\ndecimal('work_hours', 8, 2)->default(1.0)->comment('任务工时');\ndecimal('equipment_cost', 8, 2)->default(0)->comment('设备费用');\ndecimal('other_cost', 8, 2)->default(0)->comment('其他费用');\ndecimal('receivables', 8, 2)->default(0)->comment('结算金额');\ndecimal('tax', 8, 2)->default(0)->comment('税费');\ninteger('account_id')->default(0)->comment('结算编号');\nstring('remark')->nullable()->comment('任务备注');",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksIndex"
  },
  {
    "type": "get",
    "url": "/api/tasks/TaskCars",
    "title": "9.获取出勤车辆列表",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.TaskCars\n2、必选参数\ntask_id 必选参数，正整数\n特殊说明：\n1、只返回单个任务的出勤车辆，无需分页\n2、后期再增加返回相应关联字段",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksTaskcars"
  },
  {
    "type": "get",
    "url": "/api/tasks/TaskMans",
    "title": "91.获取出勤人员列表",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.TaskMans\n2、必选参数\ntask_id 必选参数，正整数\n特殊说明：\n1、只返回单个任务的出勤人员，无需分页\n2、后期再增加返回相应关联字段",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "GetApiTasksTaskmans"
  },
  {
    "type": "post",
    "url": "/api/tasks/addTaskCar",
    "title": "7.新增或编辑出勤车辆",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.addTaskCar\n2、必选参数\nid  车辆出勤编号    大于0表示编辑，否则为新增\ntask_id 任务编号   必须大于0，且为真实存在的任务编号\ncar_id 出勤车辆编号 必须大于0，且为存在的车辆\nstart_time 开始时间 datetime\n3、可选参数\nend_time 结束时间 datetime\nrent_cost 车费 2位小数\noil_cost 油费 2位小数\ntoll_cost 路费 2位小数\npark_cost 停车费 2位小数\naward_salary 奖惩工资 2位小数\nscore 任务评分 2位小数\nremark 出勤备注 string",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksAddtaskcar"
  },
  {
    "type": "post",
    "url": "/api/tasks/addTaskMan",
    "title": "5.新增或编辑出勤人员",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.addTaskMan\n2、必选参数\nid  人员出勤编号    大于0表示编辑，否则为新增\ntask_id 任务编号   必须大于0，且为真实存在的任务编号\nuser_id 出勤人员编号 必须大于0，且为存在的人员\nstart_time 开始时间 datetime\npost 岗位 string 20\n3、可选参数\nend_time 结束时间 datetime\nwork_hours 任务工时 2位小数\nwork_salary 岗位工资 2位小数\nextra_hours 加班工时 2位小数\nextra_salary 加班工资 2位小数\naward_salary 奖惩工资 2位小数\nscore 任务评分 2位小数\nremark 出勤备注 string",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksAddtaskman"
  },
  {
    "type": "post",
    "url": "/api/tasks/create",
    "title": "2.新增任务",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.create\n2、必选参数：\ncheck_time 登记时间  datetime\ncustomer_id 客户编号 integer\ntitle 任务名称  string 30\n3、可选参数\ntype 任务类型 string 20  默认值\"默认类型\"\nlinkman 联系人 string 20\nphone 联系电话 string 20\nstation 工作地点 string 60\nstart_time 开始时间 datetime\nremark 任务备注 string",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksCreate"
  },
  {
    "type": "post",
    "url": "/api/tasks/delete",
    "title": "4.删除任务",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.delete\n必填参数：\nID 任务编号 正整数\n2、code为1正常删除，为0请读取info显示原因",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksDelete"
  },
  {
    "type": "post",
    "url": "/api/tasks/delTaskCar",
    "title": "8.删除出勤车辆",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.delTaskCar\n2、必选参数\nid 车辆出勤编号",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksDeltaskcar"
  },
  {
    "type": "post",
    "url": "/api/tasks/delTaskMan",
    "title": "6.删除出勤人员",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.delTaskMan\n2、必选参数\nid  人员出勤编号",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksDeltaskman"
  },
  {
    "type": "post",
    "url": "/api/tasks/setOption",
    "title": "96.更新系统设置",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.setOption\n2、key_name 字符串\n3、key_value 逗号分隔的字符串",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksSetoption"
  },
  {
    "type": "post",
    "url": "/api/tasks/update",
    "title": "3.更新任务",
    "group": "任务管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、路由名称 tasks.update\n2、必填参数：\nID 任务编号 正整数\n3、可选参数\nstart_time 开始时间 日期时间型\nend_time 结束时间 日期时间型\nwork_hours 工时 2位小数\nequipment_cost 设备费用 2位小数\nother_cost 其他费用 2位小数\nreceivables 结算费用 2位小数\ntax 税费 2位小数\ntype 任务类型 字符串\nlinkman 联系人 字符串\nphone 联系电话 字符串\nstation 工作地点 字符串\nremark 任务备注 字符串\n4、特别说明：\n这些字段不可修改：check_time 登记时间,customer_id客户ID,title任务标题",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./TaskController.php",
    "groupTitle": "任务管理",
    "name": "PostApiTasksUpdate"
  },
  {
    "type": "get",
    "url": "/api/employees/index",
    "title": "1.登录员工个人信息",
    "group": "员工登录后模块",
    "description": "<p>该模块说明：1、当用户登录后role属性中没有admin时，只允许访问该模块的路由， 配合到前端界面也就是只显示员工查询的对应功能和菜单，功能如下：</p> <p>1、登录后可以显示员工自身信息 2、可以查询自己的任务情况 3、可以查询自己的奖惩和预支情况 4、可以查询自己的工资发放情况 5、其余功能均不可见</p> <p>路由名称 employees.index</p>",
    "version": "0.0.0",
    "filename": "./EmployeeController.php",
    "groupTitle": "员工登录后模块",
    "name": "GetApiEmployeesIndex"
  },
  {
    "type": "get",
    "url": "/api/employees/tasks",
    "title": "2.登录员工任务查询",
    "group": "员工登录后模块",
    "description": "<p>路由名称 employees.tasks</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "is_account",
            "description": "<p>是否结算，可选：-1未计算 0全部 1已经结算</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./EmployeeController.php",
    "groupTitle": "员工登录后模块",
    "name": "GetApiEmployeesTasks"
  },
  {
    "type": "post",
    "url": "/api/employees/accounts",
    "title": "4.登录员工收支信息查询",
    "group": "员工登录后模块",
    "description": "<p>路由名称 employees.accounts</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./EmployeeController.php",
    "groupTitle": "员工登录后模块",
    "name": "PostApiEmployeesAccounts"
  },
  {
    "type": "post",
    "url": "/api/employees/pays",
    "title": "3.登录员工预支奖惩查询",
    "group": "员工登录后模块",
    "description": "<p>路由名称 employees.pays</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "is_account",
            "description": "<p>是否结算，可选：-1未计算 0全部 1已经结算，默认0表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型，可选：奖励、惩罚、预支，默认空表示全部</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./EmployeeController.php",
    "groupTitle": "员工登录后模块",
    "name": "PostApiEmployeesPays"
  },
  {
    "type": "delete",
    "url": "/api/customers/:id",
    "title": "5.删除指定的客户",
    "group": "客户管理",
    "success": {
      "examples": [
        {
          "title": "简要说明",
          "content": "路由名称 customers.delete\nHTTP/1.1 200 OK\n作为URL的ID参数必填，成功code为1，否则为0",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CustomerController.php",
    "groupTitle": "客户管理",
    "name": "DeleteApiCustomersId"
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
          "content": "1、默认返回全部数据的分页显示\n2、可选参数：\nname 值不为空时表示 like方式过滤\nphone_number 值不为空时表示 like方式过滤\nstate 值不为空时表示 like方式过滤",
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
    "url": "/api/getIssues",
    "title": "94.获取建议内容列表",
    "group": "用户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "不需要任何参数",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "GetApiGetissues"
  },
  {
    "group": "用户管理",
    "type": "get",
    "url": "/api/testapi",
    "title": "0.测试api",
    "description": "<p>测试api工作情况，直接返回用户输入</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "id",
            "defaultValue": "1",
            "description": "<p>参数说明</p>"
          }
        ]
      }
    },
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "1、服务器工作正常的时候，用浏览器等访问可返回传入的任何信息",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "用户管理",
    "name": "GetApiTestapi"
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
          "content": "    {\n      \"content-type\": \"application/form-data\"\n    }\n返回情况请看postman调试结果,上传成功后返回图片的URL，该地址可作为用户创建或更新接口的avatar参数",
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
    "url": "/api/setIssues",
    "title": "95.更新建议内容",
    "group": "用户管理",
    "header": {
      "examples": [
        {
          "title": "简要说明",
          "content": "context 字符串内容\nid 具体某一条的ID，大于0表示更新，小于1表示新增\ntype 字符串内容，不大于50长度",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "用户管理",
    "name": "PostApiSetissues"
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
    "type": "delete",
    "url": "/api/role/:id",
    "title": "删除指定的角色信息",
    "group": "用户角色管理",
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
    "groupTitle": "用户角色管理",
    "name": "DeleteApiRoleId"
  },
  {
    "type": "get",
    "url": "/api/role",
    "title": "显示角色列表",
    "group": "用户角色管理",
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
    "groupTitle": "用户角色管理",
    "name": "GetApiRole"
  },
  {
    "type": "get",
    "url": "/api/role/:id",
    "title": "获取一条角色",
    "group": "用户角色管理",
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
    "groupTitle": "用户角色管理",
    "name": "GetApiRoleId"
  },
  {
    "type": "patch",
    "url": "/api/role/:id",
    "title": "更新角色信息",
    "group": "用户角色管理",
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
    "groupTitle": "用户角色管理",
    "name": "PatchApiRoleId"
  },
  {
    "type": "post",
    "url": "/api/role",
    "title": "新建一条角色信息",
    "group": "用户角色管理",
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
    "groupTitle": "用户角色管理",
    "name": "PostApiRole"
  },
  {
    "type": "get",
    "url": "/api/accounts/getAccountCar",
    "title": "9.返回截至日期前需要结算的车辆列表",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.getAccountCar</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "GetApiAccountsGetaccountcar"
  },
  {
    "type": "get",
    "url": "/api/accounts/getAccountCarListById",
    "title": "91.返回指定结算ID的车辆结算的详情列表",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.getAccountCarListById</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "accountID",
            "description": "<p>结算编号，如果是还未结算的详情，直接传递车辆ID的【负数】</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "GetApiAccountsGetaccountcarlistbyid"
  },
  {
    "type": "get",
    "url": "/api/accounts/getAccountUser",
    "title": "8.返回截至日期前需要结算的员工列表",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.getAccountUser</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "GetApiAccountsGetaccountuser"
  },
  {
    "type": "get",
    "url": "/api/accounts/getAccountUserListById",
    "title": "81.返回指定结算ID的人员结算的详情列表",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.getAccountUserListById</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "accountID",
            "description": "<p>结算编号，如果是还未结算前的详情，直接传递人员ID的【负数】</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "GetApiAccountsGetaccountuserlistbyid"
  },
  {
    "type": "get",
    "url": "/api/accounts/getone",
    "title": "2.获取某条收支记录",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.getone</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "account_id",
            "description": "<p>收支编号</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "GetApiAccountsGetone"
  },
  {
    "type": "get",
    "url": "/api/accounts/index",
    "title": "1.财务收支列表",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.index</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始日期，格式：2018-01-01</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "account_type",
            "description": "<p>收支类型，精准匹配，范围：-1，0，1，分别表示支出、全部、收入，默认值0</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "object_type",
            "description": "<p>收支对象类型，精准匹配，范围：员工结算、车辆结算、客户结算及自定义字符串，默认值空表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "object_id",
            "description": "<p>收支对象ID，精准匹配，只有在object_type为员工结算或车辆结算或者客户结算时有意义，默认值空表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "trade_type",
            "description": "<p>交易类型，精准匹配，默认值空，表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "object_name",
            "description": "<p>收支对象名称，模糊匹配，默认值空表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "handler",
            "description": "<p>经办人，模糊匹配，默认值空表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "remark",
            "description": "<p>备注，模糊匹配，默认值空，表示全部</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "pageSize",
            "description": "<p>分页大小，默认值15</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "GetApiAccountsIndex"
  },
  {
    "type": "post",
    "url": "/api/accounts/accountcar",
    "title": "92.与车辆结算某个时间点之前的工资",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.accountcar</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "car_id",
            "description": "<p>车辆ID编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account_time",
            "description": "<p>结算日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "handler",
            "description": "<p>经办人，默认登录用户</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "trade_type",
            "description": "<p>交易类型，如：现金、支付宝、微信、银行卡、对公账户等</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "trade_account",
            "description": "<p>交易账户号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "PostApiAccountsAccountcar"
  },
  {
    "type": "post",
    "url": "/api/accounts/accounttask",
    "title": "5.与客户结算某个任务",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.accounttask 注意，此接口只用于与客户结算某个任务，结算后不可删除结算记录，任务结算状态不可再更改 结算金额自动为该任务的结算金额，如果以后修改任务后，系统自动修改对应的结算金额</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account_time",
            "description": "<p>结算日期</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "task_id",
            "description": "<p>任务ID编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "handler",
            "description": "<p>经办人，默认登录用户</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "trade_type",
            "description": "<p>交易类型，如：现金、支付宝、微信、银行卡、对公账户等</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "trade_account",
            "description": "<p>交易账户号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "PostApiAccountsAccounttask"
  },
  {
    "type": "post",
    "url": "/api/accounts/accountuser",
    "title": "82.与员工结算某个时间点之前的工资",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.accountuser 注意，此接口只用于与员工结算某个时间点之前的工资，结算后不可删除结算记录，所有参与结算的任务和奖惩记录的结算状态均不可再更改 结算金额自动为该时间点之前的出勤任务及奖惩记录的合计金额，如果以后修改任务情况和奖惩记录后，系统自动修改对应的收支记录</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "user_id",
            "description": "<p>人员ID编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account_time",
            "description": "<p>结算日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_time",
            "description": "<p>截至日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "handler",
            "description": "<p>经办人，默认登录用户</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "trade_type",
            "description": "<p>交易类型，如：现金、支付宝、微信、银行卡、对公账户等</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "trade_account",
            "description": "<p>交易账户号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "PostApiAccountsAccountuser"
  },
  {
    "type": "post",
    "url": "/api/accounts/delete",
    "title": "4.删除指定的手动收支信息",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.delete 注意，此处id默认为0，如果没获取到id将返回错误消息：未找到对应编号0的收支信息 只能删除收支对象object_type不为 客户结算、员工结算、车辆结算 的信息</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>收支编号，正整数</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "PostApiAccountsDelete"
  },
  {
    "type": "post",
    "url": "/api/accounts/saveone",
    "title": "3.新增或更新一条手动收支信息",
    "group": "财务管理",
    "description": "<p>路由名称 accounts.saveone</p> <p>注意，当object_type为客户结算、员工结算、车辆结算时，object_type不允许更改且该记录不允许删除</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>收支编号，整数，小于1表示新增</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account_time",
            "description": "<p>收支时间,日期，格式2018-01-01</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "object_type",
            "description": "<p>收支对象，可选项：除【客户结算、员工结算、车辆结算】之外的自定义字符串</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "account_type",
            "description": "<p>收支类型，可选项：1=收入、-1=支出</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "object_id",
            "description": "<p>收支对象ID，当object_type为客户结算、员工结算、车辆结算的时候，表示客户、员工、车辆的ID，其余必须为0</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "object_name",
            "description": "<p>对象名称，当object_type为客户结算、员工结算、车辆结算的时候，表示客户、员工、车辆的名称，其余为自定义字符串</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "handler",
            "description": "<p>经办人，默认登录用户</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "money",
            "description": "<p>金额，2位小数</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "trade_type",
            "description": "<p>交易账户类型，如支付宝、现金等</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "trade_account",
            "description": "<p>交易账户号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccountController.php",
    "groupTitle": "财务管理",
    "name": "PostApiAccountsSaveone"
  },
  {
    "type": "delete",
    "url": "/api/cars/:id",
    "title": "5.删除指定的车辆",
    "group": "车辆管理",
    "success": {
      "examples": [
        {
          "title": "简要说明",
          "content": "路由名称 cars.delete\nHTTP/1.1 200 OK\n作为URL的ID参数必填，成功code为1，否则为0",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./CarController.php",
    "groupTitle": "车辆管理",
    "name": "DeleteApiCarsId"
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
