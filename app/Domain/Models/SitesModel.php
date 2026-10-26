<?php

namespace App\Domain\Models;

class SitesModel extends BaseModel
{
    private const FILTER_CONFIG = [
        'site_name' => ['column' => 'site_name', 'type' => 'contains'],
        'site_type' => ['column' => 'site_type', 'type' => 'contains'],
        'discovery_date_between' => ['column' => 'discovery_date', 'type' => 'date_between'],
        'age_min' => ['column' => 'estimated_age', 'type' => 'min', 'minAllowed' => 0, 'maxAllowed' => 600000000],
        'age_max' => ['column' => 'site_name', 'type' => 'min', 'minAllowed' => 0, 'maxAllowed' => 600000000],
        'country' => ['column' => 'country', 'type' => 'contains'],
        'region' => ['column' => 'region', 'type' => 'contains'],
        'is_protected' => ['column' => 'is_protected', 'type' => 'boolean'],
        'culture_id' => ['column' => 'culture_id', 'type' => 'exact'],
        'period_id' => ['column' => 'period_id', 'type' => 'exact'],

    ];
    private const SORTABLE_FIELDS = [
        'site_name',
        'site_type',
        'discovery_date',
        'estimated_age',
        'country',
        'region',
        'culture_id',
        'period_id'
    ];


    public function getSites(array $filters): array
    {
        return $this->getCollection($filters, 'archeological_site', self::FILTER_CONFIG, self::SORTABLE_FIELDS);
    }

    public function getSitesById(int $site_id): array | false
    {
        return $this->getCollectionById($site_id, 'archeological_site', 'site_id');
    }

    public function getSiteCulture(array $filters, int $site_id): array | false
    {
        return $this->getSubCollection($site_id, 'archeological_site', 'cultures', 'culture_id', self::FILTER_CONFIG, self::SORTABLE_FIELDS);
    }

    public function getSitePeriod(array $filters, int $site_id): array | false
    {
        return $this->getSubCollection($site_id, 'archeological_site', 'historical_period', 'period_id', self::FILTER_CONFIG, self::SORTABLE_FIELDS);
    }

    public function createSite(array $sites)
    {
        return $this->insert('archeological_sites', $sites);
    }
}
