CREATE TABLE parts(part_id INTEGER NOT NULL PRIMARY KEY,
  part_name VARCHAR(60) NOT NULL);

CREATE TABLE components(comp_id INTEGER NOT NULL PRIMARY KEY,
  comp_name VARCHAR(60),
  comp_count INTEGER NOT NULL,
  comp_part INTEGER NOT NULL,
  comp_partof INTEGER,
  FOREIGN KEY(comp_part) REFERENCES parts(part_id));
ALTER TABLE components ADD FOREIGN KEY(comp_partof) REFERENCES components(comp_id);


INSERT INTO parts VALUES(1, 'Car');
INSERT INTO parts VALUES(2, 'Bolt');
INSERT INTO parts VALUES(3, 'Nut');
INSERT INTO parts VALUES(4, 'V8 engine');
INSERT INTO parts VALUES(5, '6-cylinder engine');
INSERT INTO parts VALUES(6, '4-cylinder engine');
INSERT INTO parts VALUES(7, 'Cylinder block');
INSERT INTO parts VALUES(8, 'Cylinder');
INSERT INTO parts VALUES(9, 'Piston');
INSERT INTO parts VALUES(10, 'Camshaft');
INSERT INTO parts VALUES(11, 'Camshaft bearings');
INSERT INTO parts VALUES(12, 'Body');
INSERT INTO parts VALUES(13, 'Gearbox');
INSERT INTO parts VALUES(14, 'Chassie');
INSERT INTO parts VALUES(15, 'Rear axle');
INSERT INTO parts VALUES(16, 'Rear break');
INSERT INTO parts VALUES(17, 'Wheel');
INSERT INTO parts VALUES(18, 'Wheel bolts');

INSERT INTO components VALUES(1, '320', 1, 1, NULL);
INSERT INTO components VALUES(2, NULL, 1, 6, 1);
INSERT INTO components VALUES(3, NULL, 1, 7, 2);
INSERT INTO components VALUES(4, NULL, 4, 8, 3);
INSERT INTO components VALUES(5, NULL, 4, 9, 3);
INSERT INTO components VALUES(6, NULL, 1, 10, 3);
INSERT INTO components VALUES(7, NULL, 3, 11, 6);
INSERT INTO components VALUES(8, NULL, 1, 12, 1);
INSERT INTO components VALUES(9, NULL, 1, 14, 1);
INSERT INTO components VALUES(10, NULL, 1, 15, 9);
INSERT INTO components VALUES(11, NULL, 2, 16, 10);

INSERT INTO components VALUES(12, '323 i', 1, 1, NULL);
INSERT INTO components VALUES(13, NULL, 1, 5, 12);
