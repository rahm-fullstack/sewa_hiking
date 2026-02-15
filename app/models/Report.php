<?php
class Report
{

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
