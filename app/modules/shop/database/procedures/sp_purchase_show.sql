DROP PROCEDURE IF EXISTS sp_purchase_show;

CREATE PROCEDURE sp_purchase_show (IN p_id INT) BEGIN
-- =========================
-- PURCHASE
-- =========================
SELECT
	p.id,
	p.supplier_id,
	s.name AS supplier_name,
	p.warehouse_id,
	w.name AS warehouse_name,
	p.description,
	p.note,
	p.status,
	p.payment,
	p.subtotal_amount,
	p.vat_rate,
	p.vat_amount,
	p.total_amount,
	p.paid_amount,
	p.debt_amount,
	p.created_by,
	p.created_at,
	p.updated_at
FROM
	purchases p
	LEFT JOIN suppliers s ON s.id = p.supplier_id
	LEFT JOIN warehouses w ON w.id = p.warehouse_id
WHERE
	p.id = p_id;

-- =========================
-- ITEMS
-- =========================
SELECT
	pi.id,
	pi.purchase_id,
	pi.product_id,
	pr.name AS product_name,
	pi.quantity,
	pi.purchase_price,
	pi.selling_price,
	pi.subtotal_amount,
	pi.vat_rate,
	pi.vat_amount,
	pi.total_amount,
	pi.total_amount_with_vat
FROM
	purchase_items pi
	LEFT JOIN products pr ON pr.id = pi.product_id
WHERE
	pi.purchase_id = p_id;

END
