ALTER TABLE `oc_customer`
ADD COLUMN `sms_code` VARCHAR(45) NOT NULL DEFAULT '' AFTER `date_added`;

ALTER TABLE `oc_customer`
ADD COLUMN `sms_date` BIGINT NOT NULL DEFAULT 0 AFTER `sms_code`;

UPDATE oc_customer
SET telephone = CONCAT(
    '380',
    RIGHT(
      REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
            telephone, '+', ''),
        '-', ''),
        '_', ''),
        ' ', ''),
        '(', ''),
        ')', ''),
        'X', ''),
        'x', ''),
    9)
)
WHERE telephone not REGEXP '^(\\+?380|0)?[0-9]{9}$' and customer_id>0;

-- ttn
ALTER TABLE `oc_order`
ADD COLUMN `ttn` VARCHAR(100) NOT NULL DEFAULT '' AFTER `date_modified`,
ADD COLUMN `ms_id` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ttn`;


-- Personal discont
ALTER TABLE `oc_customer`
ADD COLUMN `discont` INT NOT NULL AFTER `sms_date`;
