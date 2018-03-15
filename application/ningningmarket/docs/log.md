# ningningmarket

* Thu Mar 15 17:01:07 GMT 2018 拟放弃Model，采用VC结构。

* Thu Mar 15 17:02:12 GMT 2018 已开发的方法:

   + https://brocadesoar.cn/ningningmarket/item_man/items
      - POST hash
   + https://brocadesoar.cn/ningningmarket/item_man/item 
      - POST hash, id
   + https://brocadesoar.cn/ningningmarket/item_man/registerItem
      - POST hash, name, price, stock
   + https://brocadesoar.cn/ningningmarket/item_man/removeItem
      - POST hash, id

* Thu Mar 15 17:09:37 GMT 2018 接下来要完成登陆授权机制

   + https://brocadesoar.cn/ningningmarket/Auth/...

* Thu Mar 15 17:10:42 GMT 2018 最迫切的任务是数据库创建事件，来确保30min掉线机制
