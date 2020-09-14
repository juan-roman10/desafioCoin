INSERT INTO `companies` (`id`, `name`) VALUES
(1, 'dummy1'),
(2, 'dummy2'),
(3, 'dummy3');
COMMIT;

INSERT INTO `payment_methods` (`id`, `name`) VALUES
(1, 'VISA'),
(2, 'AMEX'),
(3, 'MASTER');
COMMIT;

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'CONFIRMED'),
(2, 'REVERSED'),
(3, 'INITIALIZED');
COMMIT;


