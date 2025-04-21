TRUNCATE TABLE categories;
INSERT INTO categories(id, name, created_at, updated_at)
VALUES (1, 'laravel', NOW(), NOW()),
       (2, 'Vue', NOW(), NOW()),
       (3, 'Tailwind', NOW(), NOW());