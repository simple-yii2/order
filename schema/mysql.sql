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
    `subtotalAmount` decimal(10,2) not null,
    `deliveryAmount` decimal(10,2) not null,
    `totalAmount` decimal(10,2) not null,
    `phone` varchar(20) not null,
    `email` varchar(100) not null,
    `name` varchar(100) not null,
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
    primary key (`order_id`, `product_id`),
    foreign key (`order_id`) references `purchase_order` (`id`) on delete cascade on update cascade
) engine InnoDB;

create table if not exists `purchase_order_delivery`
(
    `id` int(10) not null auto_increment,
    `order_id` int(10) not null,
    `class` varchar(200) not null,
    `name` varchar(100) not null,
    `amount` decimal(10,2) not null,
    `days` int(10) not null,
    `_fields` text not null,
    `store_id` int(10) default null,
    `serviceName` varchar(100) default null,
    `city` varchar(100) default null,
    `street` varchar(100) default null,
    `house` varchar(10) default null,
    `apartment` varchar(10) default null,
    `entrance` varchar(100) default null,
    `entryphone` varchar(10) default null,
    `floor` int(10) default null,
    `recipient` varchar(100) default null,
    `phone` varchar(20) default null,
    `comment` varchar(200) default null,
    `trackCode` varchar(20) default null,
    primary key (`id`),
    unique key (`order_id`),
    foreign key (`order_id`) references `purchase_order` (`id`) on delete cascade on update cascade
) engine InnoDB;
