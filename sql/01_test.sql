SELECT 'geotek' AS table_name, COUNT(*) AS `rows` FROM geotek
UNION ALL
SELECT 'rz', COUNT(*) FROM rz;

-- Search examples
SELECT * FROM geotek WHERE code LIKE '%GTK%' LIMIT 50;
SELECT * FROM rz WHERE section='ENT' AND name LIKE '%ZANGE%' LIMIT 50;

-- Insert example (edit then run)
-- INSERT INTO geotek (section, name, code, description, size, packaging)
-- VALUES ('Needle Guide', 'Disposable Needle Guide', 'GTK999', 'Test insert from VS Code', 'N/A', 'Sterile');
