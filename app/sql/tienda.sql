
CREATE DATABASE tienda
    DEFAULT CHARACTER SET utf8;

USE tienda;

CREATE TABLE category (
    reference VARCHAR(20) NOT NULL UNIQUE,
    category_url VARCHAR(50) NOT NULL UNIQUE,
    alias VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (reference)
) ENGINE=INNODB;

CREATE TABLE product (
    category_ref VARCHAR(20) NOT NULL,
    reference VARCHAR(20) NOT NULL UNIQUE,
    img_url VARCHAR(255) NOT NULL,
    alias VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    information TEXT CHARACTER SET utf8,
    cost FLOAT(8,2) NOT NULL,
    price FLOAT(8,2) NOT NULL,
    offer BOOLEAN NOT NULL DEFAULT 0,
    discount_percentage INT NOT NULL DEFAULT 0,
    position INT NOT NULL DEFAULT 0,
    quantity INT NOT NULL DEFAULT 0,
    min_stock INT NOT NULL DEFAULT 0,
    date_add DATETIME NOT NULL,
    PRIMARY KEY (reference),
    FOREIGN KEY (category_ref)
        REFERENCES category (reference)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
) ENGINE=INNODB; 

CREATE TABLE featured (
    product_ref VARCHAR(20) NOT NULL UNIQUE,
    date_add DATETIME NOT NULL,
    PRIMARY KEY (product_ref),
    FOREIGN KEY (product_ref)
        REFERENCES product (reference)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE sale_information (
    reference VARCHAR(20) NOT NULL UNIQUE,
    date_day DATE NOT NULL,
    hour TIME NOT NULL,
    position INT NOT NULL DEFAULT 0,
    PRIMARY KEY (reference)
) ENGINE=INNODB;

CREATE TABLE sale_details (
    reference_sal VARCHAR(20) NOT NULL,
    reference_pro VARCHAR(20) NOT NULL,
    quantity INT NOT NULL,
    subtotal FLOAT(10,2) NOT NULL,
    total FLOAT(10,2) NOT NULL,
    PRIMARY KEY (reference_sal, reference_pro),
    FOREIGN KEY (reference_sal)
        REFERENCES sale_information (reference)
            ON UPDATE CASCADE
            ON DELETE RESTRICT,
    FOREIGN KEY (reference_pro)
        REFERENCES product (reference)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE current_sale (
    reference_pro VARCHAR(20) NOT NULL,
    quantity INT NOT NULL,
    subtotal FLOAT(10,2) NOT NULL,
    total FLOAT(10,2) NOT NULL,
    date_add DATETIME NOT NULL,
    PRIMARY KEY (reference_pro),
    FOREIGN KEY (reference_pro)
        REFERENCES product (reference)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE settings (
    reference VARCHAR(20) NOT NULL UNIQUE,
    username VARCHAR(20) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    mission TEXT CHARACTER SET utf8,
    vision TEXT CHARACTER SET utf8,
    fb_link VARCHAR(255) NOT NULL,
    wp_number VARCHAR(50) NOT NULL
) ENGINE=INNODB; 


INSERT INTO `settings` (`reference`, `username`, `pass`, `mission`, `vision`, `fb_link`, `wp_number`) VALUES ('general_information', 'user.admin', '$2y$10$FLg1h/uFk3HwyFNzl0BYru6asFezrzhhNNOJY/LADUDPKkv.310Fq', 'Ofrecer a nuestros exigentes clientes una gama de regalos para cada ocasión, encontrando el producto de calidad que usted desea para su personal o proveedor, con los mejores precios del mercado y en el tiempo que usted lo requiera...Regalos y Novedades Liz es el arte de regalar soluciones originales.', 'Ser una empresa lider en ofrecer a nuestros clientes obsequios y regalos para esas fechas especiales, ofreciendo el mejor servicio y cubriendo las necesidades y requerimientos del cliente, siendo reconocidos por la calidad de nuestros servicios y productos.', 'https://www.facebook.com/Regalos-Y-Novedades-Liz-269069383946063', '522271130442');

INSERT INTO `category` (`reference`, `category_url`, `alias`) VALUES ('categoria_0001', 'ninos', 'Niños'), ('categoria_0002', 'damas', 'Damas'), ('categoria_0003', 'caballeros', 'Caballeros');




INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0001', 'producto_0001', 'caballeros/libros.jpg', 'El Gran Gatsby.', 'Autor: Francis Scott Fitzgerald', '', 434.00, 600.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0002', 'producto_0002', 'caballeros/libros.jpg', 'El producto num 2.', 'Autor: Francis numero 2', '', 434.00, 600.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0001', 'producto_0003', 'caballeros/libros.jpg', 'El producto num 3.', 'Autor: Francis numero 3', '', 454.00, 700.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0003', 'producto_0004', 'caballeros/libros.jpg', 'El gran producto num 4.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0002', 'producto_0005', 'caballeros/libros.jpg', 'El producto num 5.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0002', 'producto_0006', 'caballeros/libros.jpg', 'El producto num 6.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0001', 'producto_0007', 'caballeros/libros.jpg', 'El producto num 7.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0003', 'producto_0008', 'caballeros/libros.jpg', 'El producto num 8.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0001', 'producto_0009', 'caballeros/libros.jpg', 'El producto num 9.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0003', 'producto_0010', 'caballeros/libros.jpg', 'El producto num 10.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());

INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `date_add`) VALUES
('categoria_0001', 'producto_0011', 'caballeros/libros.jpg', 'El producto num 11.', 'Autor: Francis numero 4', '', 364.00, 750.00, 0, 0, 0, NOW());




INSERT INTO `featured` (`product_ref`, `date_add`) VALUES ('producto_0005', '2021-11-03 00:00:00');
INSERT INTO `featured` (`product_ref`, `date_add`) VALUES ('producto_0010', '2021-11-05 00:00:00');
INSERT INTO `featured` (`product_ref`, `date_add`) VALUES ('producto_0001', '2021-11-06 00:00:00');
INSERT INTO `featured` (`product_ref`, `date_add`) VALUES ('producto_0011', '2021-11-09 00:00:00');
INSERT INTO `featured` (`product_ref`, `date_add`) VALUES ('producto_0003', '2021-11-12 00:00:00');
INSERT INTO `featured` (`product_ref`, `date_add`) VALUES ('producto_0009', '2021-11-09 00:00:00'), ('producto_0007', '2021-11-16 00:00:00');



INSERT INTO `sale_information` (`reference`, `date_day`, `hour`, `position`) VALUES ('venta_0001', '2021-11-16', '10:35:16', '0');
INSERT INTO `sale_information` (`reference`, `date_day`, `hour`, `position`) VALUES ('venta_0002', '2021-15-16', '10:35:16', '0');

INSERT INTO `sale_details` (`reference_sal`, `reference_pro`, `quantity`, `subtotal`, `total`) VALUES ('venta_0001', 'producto_0001', '3', 340.56, 340.00);
INSERT INTO `sale_details` (`reference_sal`, `reference_pro`, `quantity`, `subtotal`, `total`) VALUES ('venta_0001', 'producto_0002', '2', 344.56, 320 );

INSERT INTO `sale_details` (`reference_sal`, `reference_pro`, `quantity`, `subtotal`, `total`) VALUES ('venta_0002', 'producto_0002', '2', 344.56, 320);



