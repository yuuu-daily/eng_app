TRUNCATE TABLE companies;
INSERT INTO companies(id, name, created_at, updated_at)
VALUES (1, '株式会社 A', NOW(), NOW()),
       (2, '株式会社 B', NOW(), NOW()),
       (3, '株式会社 C', NOW(), NOW());