## Yii2 数据请求参数，自动转成查询条件；总结说明

### 1.由接口请求的数据，转成查询条件

#### 1.1 表单数据如下
```json
   {
   	"page": "1",
   	"per-page": "10",
   	"filter": {
   		"status": "1",
   		"name": {
   			"like": "庆"
   		},
   		"user_id": {
   			"in": ["1", "2"]
   		},
   		"city_id": {
   			"nin": ["100", "101"]
   		},
   		"create_time": {
   			"gt": "2020-05-08 11:34:23",
   			"lte": "2020-10-08 11:34:23"
   		},
   		"gender": {
   			"neq": "男"
   		},
   		"or": [{
   			"status": {
   				"eq": "1"
   			}
   		}, {
   			"name": {
   				"eq": "王五"
   			}
   		}]
   	}
   }
  
```

#### 1.2 GET参数如下（前端人员参考）

```html
    {{baseUrl}}?filter[status]=0&filter[name][like]=aa&filter[id][in][]=1&filter[id][in][]=2&filter[create_time][gt]=2020-05-08%2011:34:23&page=1&per-page=10
    //由于前端在拼装 in 查询的时候比较麻烦；所以【in】支持 逗号分隔的查询模式，如下:
    {{baseUrl}}?filter[id][in][]=2,3,10
```

    
#### 1.3 PHP处理JSON的表单

```php

  $dataFilter = new DataFilter();
  $dataFilter->load(Yii::$app->request->getBodyParams());
  /**
  构造出查询条件：如下
  ["AND", {
  		"status": "0"
  	},
  	["LIKE", "name", "老王"],
  	["IN", "id", ["1", "2"]],
  	[">", "create_time", "2020-05-08 11:34:23"]
  ]
  */
  $filterCondition = $dataFilter->build(false);
  
  
```

#### 1.4 支持的查询条件有哪些(前端参看)
     
```json
{
    "and": "AND",
    "or": "OR",
    "not": "NOT",
    "lt": "<",
    "gt": ">",
    "lte": "<=",
    "gte": ">=",
    "eq": "=",
    "neq": "!=",
    "in": "IN",
    "nin": "NOT IN",
    "like": "LIKE"
}
/*
    示例
    page:1
    per-page:10
    filter[status]:1
    filter[name][like]:庆
    filter[user_id][in][]:1
    filter[user_id][in][]:2
    filter[city_id][nin][]:100
    filter[city_id][nin][]:101
    filter[create_time][gt]:2020-01-01 11:34:23
    filter[create_time][lte]:2020-01-01 11:34:23
    filter[gender][neq]:男
    filter[or][][status][eq]:1
    filter[or][][name][eq]:王五
*/

```

#### 1.5 上面对应查询条件对应的sql语句

```sql

    SELECT
        * 
    FROM
        `user` 
    WHERE
        ( `status` = '1' ) 
        AND ( `name` LIKE '%庆%' ) 
        AND ( `user_id` IN ( '1', '2' ) ) 
        AND ( `city_id` NOT IN ( '100', '101' ) ) 
        AND ( ( `create_time` > '2020-05-08 11:34:23' ) AND ( `create_time` <= '2020-10-08 11:34:23' ) ) 
        AND ( `gender` != '男' ) 
        AND ( ( `status` = '1' ) OR ( `name` = '王五' ) ) 
    ORDER BY
        `id` DESC
	
```


### 2.由JSON自动转成查询方式

#### 2.1 JSON格式如下
```json
    {
        "page": 1,
        "per-page": 10,
        "filter": [
            "AND",
            {
                "status": ""
            },
            [
                "LIKE",
                "name",
                "老王"
            ],
            [
                "LIKE",
                "contact_name",
                ""
            ],
            [
                "LIKE",
                "contact_phone",
                ""
            ],
             [
                "in",
                "id",
                [2,4]
            ],
            [
                ">=",
                "create_time",
                "2020-01-01 11:34:23"
            ]
        ]
    }
```
    
#### 2.2 PHP处理JSON的表单
```php
  $body = \Yii::$app->request->getRawBody();
  $body = json_decode($body, true);
  
  $filterCondition = $body['filter'];
  
  $query->andFilterWhere($filterCondition);
```