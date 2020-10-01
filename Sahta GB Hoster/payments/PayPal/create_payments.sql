
CREATE TABLE `billings` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `txnid` varchar(20) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(25) NOT NULL,
  `userid` varchar(11) NOT NULL,
  `time` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `billings`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `billings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
