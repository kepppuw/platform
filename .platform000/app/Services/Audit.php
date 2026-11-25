<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\DB;

final class Audit
{
    public function __construct(private DB $db) {}

    public function log(
        ?int $userId,
        string $action,
        ?string $entity = null,
        ?int $entityId = null,
        array $meta = [],
        string $ip = ''
    ): void {
        $this->db->query(
            'INSERT INTO audit_log (user_id, action, entity, entity_id, meta, ip) VALUES (?,?,?,?,?,?)',
            [$userId, $action, $entity, $entityId, json_encode($meta, JSON_UNESCAPED_UNICODE), $ip]
        );
    }
}