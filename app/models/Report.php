<?php
class Report
{
    public static function transactions($startDate = null, $endDate = null)
{
    $db = Database::connect();

    $sql = "
        SELECT
            r.id AS rental_id,
            u.name AS user_name,
            t.tool_name,
            rd.quantity,
            rd.price_per_day,
            (rd.quantity * rd.price_per_day) AS subtotal,
            r.start_date,
            r.end_date,
            r.status
        FROM rentals r
        JOIN users u ON r.user_id = u.id
        JOIN rental_details rd ON rd.rental_id = r.id
        JOIN tools t ON rd.tool_id = t.id
        ORDER BY r.created_at DESC
    ";

    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public static function toolReport()
{
    $db = Database::connect();

    $sql = "
        SELECT
            t.id AS tool_id,
            t.tool_name,
            c.category_name,
            SUM(rd.quantity) AS total_disewa,
            SUM(rd.quantity * rd.price_per_day) AS total_pendapatan
        FROM rental_details rd
        JOIN tools t ON rd.tool_id = t.id
        JOIN categories c ON t.category_id = c.id
        GROUP BY t.id, t.tool_name, c.category_name
        ORDER BY total_disewa DESC
    ";

    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

}
