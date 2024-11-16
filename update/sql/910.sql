UPDATE `settings` SET `value` = '{\"version\":\"9.1.0\", \"code\":\"910\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table plans add `order` int unsigned default 0 null after status;

-- SEPARATOR --

SET @rownumber = 0;

-- SEPARATOR --

update plans set `order` = @rownumber := @rownumber+1 order by plan_id;
