create table if not exists `purchase_order`
(
    `id` int(10) not null auto_increment,
    `currency_id` int(10) default null,
    `user_id` int(10) default null,
    `createDate` datetime not null,
    `modifyDate` datetime not null,
    `state` int(10) not null,
    `count` int(10) not null,
    `amount` decimal(10,2) not null,
    `discountAmount` decimal(10,2) not null,
    `totalAmount` decimal(10,2) not null,
    primary key (`id`)
) engine InnoDB;

create table if not exists `purchase_order_product`
(
    `order_id` int(10) not null,
    `product_id` int(10) not null,
    `productName` varchar(100) default null,
    `productModel` varchar(100) default null,
    `count` int(10) not null,
    `price` decimal(10,2) not null,
    `amount` decimal(10,2) not null,
    `discountAmount` decimal(10,2) not null,
    `totalAmount` decimal(10,2) not null,
    primary key (`order_id`, `product_id`)
) engine InnoDB;
