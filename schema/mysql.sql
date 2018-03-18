create table if not exists `purchase_order`
(
    `id` int(10) not null auto_increment,
    `user_id` int(10) default null,
    `createDate` datetime not null,
    `state` int(10) not null,
    `offerCount` int(10) not null,
    `totalAmount` decimal(10,2) not null,
    primary key (`id`)
) engine InnoDB;

create table if not exists `purchase_order_offer`
(
    `order_id` int(10) not null,
    `offer_id` int(10) not null,
    `offerName` varchar(100) default null,
    `offerModel` varchar(100) default null,
    `count` int(10) not null,
    `price` decimal(10,2) not null,
    `amount` decimal(10,2) not null,
    `discountAmount` decimal(10,2) not null,
    `totalAmount` decimal(10,2) not null,
    primary key (`order_id`, `offer_id`)
) engine InnoDB;
